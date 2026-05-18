<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\TrainingData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use Exception;

class TrainingController extends Controller
{
    /**
     * Get all training data for a bot.
     */
    public function index(int $botId): JsonResponse
    {
        $trainingData = TrainingData::where('bot_id', $botId)->get();
        return response()->json($trainingData);
    }

    /**
     * Store new training data (File or URL).
     */
    public function store(Request $request, int $botId): JsonResponse
    {
        $bot = Bot::findOrFail($botId);

        $request->validate([
            'file' => 'nullable|file|mimes:txt,pdf|max:10240', // 10MB max
            'url' => 'nullable|string', // Changed to string to handle protocol prepending
        ]);

        try {
            if ($request->hasFile('file')) {
                return $this->handleFileUpload($request->file('file'), $bot);
            }

            if ($request->filled('url')) {
                $url = $request->input('url');
                
                // Prepend https:// if protocol is missing
                if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                    $url = "https://" . $url;
                }

                // Final validation check
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    return response()->json([
                        'message' => 'The provided URL is invalid.',
                        'errors' => ['url' => ['Please enter a valid website URL.']]
                    ], 422);
                }

                return $this->handleUrlScrape($url, $bot);
            }

            return response()->json(['message' => 'No file or URL provided.'], 400);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to process training data.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete training data.
     */
    public function destroy(int $id): JsonResponse
    {
        $data = TrainingData::findOrFail($id);
        
        if ($data->file_path && Storage::disk('public')->exists($data->file_path)) {
            Storage::disk('public')->delete($data->file_path);
        }

        $data->delete();

        return response()->json(['message' => 'Training data deleted successfully.']);
    }

    /* ─── Private Helpers ─── */

    private function handleFileUpload($file, Bot $bot): JsonResponse
    {
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $path = $file->store('training_data', 'public');

        $content = '';
        if ($fileType === 'txt') {
            $content = file_get_contents($file->getRealPath());
        } elseif ($fileType === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getRealPath());
            $content = $pdf->getText();
        }

        $trainingData = TrainingData::create([
            'bot_id' => $bot->id,
            'file_name' => $fileName,
            'file_path' => $path,
            'file_type' => $fileType,
            'content' => $content,
            'status' => 'completed',
        ]);

        return response()->json($trainingData, 201);
    }

    private function handleUrlScrape(string $url, Bot $bot): JsonResponse
    {
        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            throw new Exception("Failed to reach the website: " . $url);
        }

        $html = $response->body();
        
        // Simple HTML stripping logic
        // 1. Remove scripts and styles
        $cleanHtml = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
        // 2. Strip tags
        $content = strip_tags($cleanHtml);
        // 3. Clean whitespace
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);

        if (empty($content)) {
            throw new Exception("No readable text found on the page.");
        }

        $trainingData = TrainingData::create([
            'bot_id' => $bot->id,
            'file_name' => parse_url($url, PHP_URL_HOST),
            'file_path' => $url,
            'file_type' => 'url',
            'content' => $content,
            'original_url' => $url,
            'status' => 'completed',
        ]);

        return response()->json($trainingData, 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\TrainingData;
use App\Jobs\ProcessTrainingData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Exception;

class TrainingController extends Controller
{
    public function index(int $botId): JsonResponse
    {
        // Don't return the raw content since it could be huge
        $trainingData = TrainingData::where('bot_id', $botId)
            ->get(['id', 'bot_id', 'file_name', 'file_type', 'status', 'created_at']);
        return response()->json($trainingData);
    }

    public function store(Request $request, int $botId): JsonResponse
    {
        $bot = Bot::findOrFail($botId);

        $request->validate([
            'file' => 'nullable|file|mimes:txt,pdf|max:10240',
            'url' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileType = $file->getClientOriginalExtension();
                $path = $file->store('training_data', 'public');

                $trainingData = TrainingData::create([
                    'bot_id' => $bot->id,
                    'file_name' => $fileName,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'status' => 'processing',
                ]);

                ProcessTrainingData::dispatch($trainingData);

                return response()->json($trainingData, 201);
            }

            if ($request->filled('url')) {
                $url = $request->input('url');
                
                if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                    $url = "https://" . $url;
                }

                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    return response()->json([
                        'message' => 'The provided URL is invalid.',
                        'errors' => ['url' => ['Please enter a valid website URL.']]
                    ], 422);
                }

                $trainingData = TrainingData::create([
                    'bot_id' => $bot->id,
                    'file_name' => parse_url($url, PHP_URL_HOST),
                    'file_path' => $url,
                    'file_type' => 'url',
                    'original_url' => $url,
                    'status' => 'processing',
                ]);

                ProcessTrainingData::dispatch($trainingData);

                return response()->json($trainingData, 201);
            }

            return response()->json(['message' => 'No file or URL provided.'], 400);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to process training data.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $data = TrainingData::findOrFail($id);
        
        if ($data->file_type !== 'url' && $data->file_path && Storage::disk('public')->exists($data->file_path)) {
            Storage::disk('public')->delete($data->file_path);
        }

        $data->delete();

        return response()->json(['message' => 'Training data deleted successfully.']);
    }
}

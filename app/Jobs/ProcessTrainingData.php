<?php

namespace App\Jobs;

use App\Models\TrainingData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Exception;

class ProcessTrainingData implements ShouldQueue
{
    use Queueable;

    public $timeout = 120; // 2 minutes

    public function __construct(public TrainingData $trainingData)
    {
    }

    public function handle(): void
    {
        try {
            $content = '';
            
            if ($this->trainingData->file_type === 'txt') {
                $content = Storage::disk('public')->get($this->trainingData->file_path);
            } elseif ($this->trainingData->file_type === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseContent(Storage::disk('public')->get($this->trainingData->file_path));
                $content = $pdf->getText();
            } elseif ($this->trainingData->file_type === 'url') {
                $response = Http::timeout(30)->get($this->trainingData->file_path);
                if (!$response->successful()) {
                    throw new Exception("Failed to reach the website.");
                }
                $html = $response->body();
                $cleanHtml = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
                $content = strip_tags($cleanHtml);
                $content = preg_replace('/\s+/', ' ', $content);
                $content = trim($content);
            }

            if (empty($content)) {
                throw new Exception("No readable text found.");
            }

            $this->trainingData->update([
                'content' => $content,
                'status' => 'completed',
            ]);
        } catch (Exception $e) {
            $this->trainingData->update([
                'status' => 'failed',
            ]);
        }
    }
}

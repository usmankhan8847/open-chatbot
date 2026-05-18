<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AIService
{
    protected string $provider;
    protected string $apiKey;
    protected string $model;
    protected ?string $baseUrl;
    protected float $temperature = 0.7;
    protected int $maxTokens = 2048;

    public function __construct()
    {
        $this->provider = env('AI_PROVIDER', 'openai');
        $this->apiKey = env('AI_API_KEY', '');
        $this->model = env('AI_MODEL', 'gpt-3.5-turbo');
        $this->baseUrl = env('AI_BASE_URL');
    }

    public function setBotConfig($bot): self
    {
        $this->provider = $bot->ai_provider ?? $this->provider;
        $this->apiKey = $bot->api_key ?? $this->apiKey;
        $this->model = $bot->ai_model ?? $this->model;
        $this->temperature = (float) ($bot->temperature ?? $this->temperature);
        $this->maxTokens = (int) ($bot->max_tokens ?? $this->maxTokens);
        
        return $this;
    }

    /**
     * Send a message to the configured AI provider.
     *
     * @param string $systemPrompt
     * @param array $messages
     * @return string
     * @throws Exception
     */
    public function sendMessage(string $systemPrompt, array $messages): string
    {
        if (empty($this->apiKey) && $this->provider !== 'custom') {
            throw new Exception("AI API Key is not configured for provider: {$this->provider}");
        }

        // Prepare messages array with system prompt
        $payloadMessages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        foreach ($messages as $msg) {
            $payloadMessages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }

        return match ($this->provider) {
            'openai' => $this->sendToOpenAI($payloadMessages),
            'anthropic' => $this->sendToAnthropic($payloadMessages),
            'gemini' => $this->sendToGemini($payloadMessages),
            'openrouter' => $this->sendToOpenRouter($payloadMessages),
            'custom' => $this->sendToCustom($payloadMessages),
            default => throw new Exception("Unsupported AI provider: {$this->provider}"),
        };
    }

    protected function sendToOpenAI(array $messages): string
    {
        $url = $this->baseUrl ?: 'https://api.openai.com/v1/chat/completions';
        
        $response = Http::withToken($this->apiKey)
            ->post($url, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $this->temperature,
                'max_tokens' => $this->maxTokens,
            ]);

        if ($response->failed()) {
            $this->handleError($response);
        }

        return $response->json('choices.0.message.content') ?? '';
    }

    protected function sendToAnthropic(array $messages): string
    {
        $url = $this->baseUrl ?: 'https://api.anthropic.com/v1/messages';
        
        // Anthropic expects system prompt separately
        $system = '';
        $userMessages = [];
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $system = $msg['content'];
            } else {
                $userMessages[] = $msg;
            }
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post($url, [
            'model' => $this->model,
            'system' => $system,
            'messages' => $userMessages,
            'temperature' => $this->temperature,
            'max_tokens' => $this->maxTokens,
        ]);

        if ($response->failed()) {
            $this->handleError($response);
        }

        return $response->json('content.0.text') ?? '';
    }

    protected function sendToGemini(array $messages): string
    {
        $url = $this->baseUrl ?: "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
        
        // Convert to Gemini format
        $contents = [];
        foreach ($messages as $msg) {
            $role = ($msg['role'] === 'assistant') ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['content']]]
            ];
        }

        $response = Http::post($url, [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $this->temperature,
                'maxOutputTokens' => $this->maxTokens,
            ],
        ]);

        if ($response->failed()) {
            $this->handleError($response);
        }

        return $response->json('candidates.0.content.parts.0.text') ?? '';
    }

    protected function sendToOpenRouter(array $messages): string
    {
        $url = $this->baseUrl ?: 'https://openrouter.ai/api/v1/chat/completions';
        
        $response = Http::withToken($this->apiKey)
            ->withHeaders([
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])
            ->post($url, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $this->temperature,
                'max_tokens' => $this->maxTokens,
            ]);

        if ($response->failed()) {
            $this->handleError($response);
        }

        return $response->json('choices.0.message.content') ?? '';
    }

    protected function sendToCustom(array $messages): string
    {
        if (empty($this->baseUrl)) {
            throw new Exception("Custom provider requires AI_BASE_URL to be configured.");
        }

        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => $this->temperature,
                'max_tokens' => $this->maxTokens,
            ]);

        if ($response->failed()) {
            $this->handleError($response);
        }

        return $response->json('choices.0.message.content') ?? $response->json('text') ?? '';
    }

    protected function handleError($response): void
    {
        $error = $response->json('error.message') ?? $response->body();
        Log::error("AI API Error ({$this->provider}): " . $error);
        throw new Exception("AI API Error: " . $error);
    }
}

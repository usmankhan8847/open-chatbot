<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ModelFetchController extends Controller
{
    /**
     * Fetch available models for a given AI provider + API key.
     *
     * POST /api/auth/models/fetch
     * Body: { provider: string, api_key: string }
     */
    public function fetch(Request $request): JsonResponse
    {
        $request->validate([
            'provider' => 'required|string|in:openai,anthropic,gemini,openrouter,custom',
            'api_key'  => 'required|string|min:8',
        ]);

        $provider = $request->input('provider');
        $apiKey   = $request->input('api_key');

        try {
            $models = match ($provider) {
                'openai'     => $this->fetchOpenAiModels($apiKey),
                'anthropic'  => $this->getAnthropicModels(),
                'gemini'     => $this->fetchGeminiModels($apiKey),
                'openrouter' => $this->fetchOpenRouterModels($apiKey),
                default      => [],
            };

            return response()->json(['models' => $models]);

        } catch (\Throwable $e) {
            return response()->json([
                'error'   => 'Failed to fetch models from provider.',
                'details' => $e->getMessage(),
            ], 400);
        }
    }

    /* ────────────────────────────────────────────────────────────────── */

    private function fetchOpenAiModels(string $apiKey): array
    {
        $response = Http::timeout(10)
            ->withHeaders(['Authorization' => 'Bearer ' . $apiKey])
            ->get('https://api.openai.com/v1/models');

        if (! $response->successful()) {
            throw new \RuntimeException('OpenAI API returned ' . $response->status());
        }

        // Keep only GPT/reasoning/o-series models, sorted by name
        return collect($response->json('data', []))
            ->filter(fn ($m) =>
                str_starts_with($m['id'], 'gpt-')    ||
                str_starts_with($m['id'], 'o1')       ||
                str_starts_with($m['id'], 'o3')       ||
                str_starts_with($m['id'], 'o4')       ||
                str_starts_with($m['id'], 'chatgpt-')
            )
            ->pluck('id')
            ->sortDesc()
            ->values()
            ->all();
    }

    private function getAnthropicModels(): array
    {
        // Anthropic has no public list endpoint; return known stable models
        return [
            'claude-opus-4-5',
            'claude-sonnet-4-5',
            'claude-haiku-4-5',
            'claude-3-5-sonnet-20241022',
            'claude-3-5-haiku-20241022',
            'claude-3-opus-20240229',
            'claude-3-sonnet-20240229',
            'claude-3-haiku-20240307',
        ];
    }

    private function fetchGeminiModels(string $apiKey): array
    {
        $response = Http::timeout(10)
            ->get('https://generativelanguage.googleapis.com/v1beta/models', [
                'key' => $apiKey,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Gemini API returned ' . $response->status());
        }

        return collect($response->json('models', []))
            ->filter(fn ($m) =>
                isset($m['name']) &&
                str_contains($m['name'], 'gemini') &&
                in_array('generateContent', $m['supportedGenerationMethods'] ?? [])
            )
            ->map(fn ($m) => str_replace('models/', '', $m['name']))
            ->sort()
            ->values()
            ->all();
    }

    private function fetchOpenRouterModels(string $apiKey): array
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer'  => config('app.url', 'http://localhost'),
            ])
            ->get('https://openrouter.ai/api/v1/models');

        if (! $response->successful()) {
            throw new \RuntimeException('OpenRouter API returned ' . $response->status());
        }

        return collect($response->json('data', []))
            ->pluck('id')
            ->sort()
            ->values()
            ->all();
    }
}

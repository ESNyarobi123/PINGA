<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->baseUrl = config('services.openai.base_url');
        $this->model = config('services.openai.model', 'llama-3.1-8b-instant');
    }

    public function translate(string $text, string $context = 'text'): string
    {
        if (empty(trim($text))) {
            return $text;
        }

        $systemPrompt = match ($context) {
            'title' => 'You are a professional Swahili-to-English translator. Translate the given Swahili job title to English. Return ONLY the translated title — one short line, no explanations, no definitions, no extra text.',
            default => 'You are a professional Swahili-to-English translator. Translate the given Swahili text to English accurately. Preserve the original formatting. Return ONLY the translated text, nothing else.',
        };

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $text,
                    ],
                ],
                'temperature' => 0.3,
                'max_tokens' => $context === 'title' ? 256 : 2048,
            ]);

            if (! $response->successful()) {
                throw new \RuntimeException(
                    "Translation API error [{$response->status()}]: {$response->body()}"
                );
            }

            $translated = trim($response->json('choices.0.message.content') ?? '');

            if (empty($translated)) {
                throw new \RuntimeException('Translation API returned empty response');
            }

            return $translated;
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \RuntimeException("Translation API call failed: {$e->getMessage()}", 0, $e);
        }
    }
}

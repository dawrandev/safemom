<?php

namespace App\Services;

use App\Models\Vital;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    /**
     * Analyze vitals using DeepSeek API
     *
     * @param Vital $vital
     * @param string $locale
     * @return array
     * @throws \Exception
     */
    public function analyzeVitals(Vital $vital, string $locale = 'en'): array
    {
        $locale = in_array($locale, ['uz', 'ru', 'en'], true) ? $locale : 'en';

        $messages = [
            ['role' => 'system', 'content' => $this->getSystemPrompt($locale)],
            ['role' => 'user', 'content' => $this->buildUserMessage($vital, $locale)],
        ];

        try {
            $response = $this->callApi($messages);
            return $this->parseResponse($response['choices'][0]['message']['content'], $locale);
        } catch (\Exception $e) {
            Log::error('DeepSeek API call failed', [
                'vital_id' => $vital->id,
                'error' => $e->getMessage(),
            ]);

            // Return safe default on error
            return $this->getSafeDefault($locale);
        }
    }

    /**
     * Get system prompt for the specified locale
     *
     * @param string $locale
     * @return string
     */
    protected function getSystemPrompt(string $locale): string
    {
        $prompts = config('deepseek.system_prompts');
        return $prompts[$locale] ?? $prompts['en'];
    }

    /**
     * Build user message with vital signs data
     *
     * @param Vital $vital
     * @param string $locale
     * @return string
     */
    protected function buildUserMessage(Vital $vital, string $locale): string
    {
        $templates = config('deepseek.user_message_templates');
        $template = $templates[$locale] ?? $templates['en'];

        return str_replace(
            ['{systolic}', '{diastolic}', '{heart_rate}', '{temperature}'],
            [$vital->systolic_bp, $vital->diastolic_bp, $vital->heart_rate, $vital->temperature],
            $template
        );
    }

    /**
     * Call DeepSeek API with retry logic
     *
     * @param array $messages
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function callApi(array $messages): array
    {
        return Http::timeout(config('deepseek.timeout'))
            ->retry(config('deepseek.max_retries'), 100, throw: false)
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('deepseek.api_key'),
            ])
            ->post(config('deepseek.api_url'), [
                'model' => config('deepseek.model'),
                'messages' => $messages,
                'temperature' => config('deepseek.temperature'),
                'max_tokens' => config('deepseek.max_tokens'),
            ])
            ->throw()
            ->json();
    }

    /**
     * Parse API response and extract JSON
     *
     * @param string $response
     * @return array
     */
    protected function parseResponse(string $response, string $locale): array
    {
        // Try to extract JSON from response
        $jsonPattern = '/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/';
        if (preg_match($jsonPattern, $response, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'status' => $decoded['status'] ?? 'yellow',
                    'analysis_text' => $decoded['analysis_text'] ?? '',
                    'nutrition_advice' => $decoded['nutrition_advice'] ?? '',
                    'is_critical' => $decoded['is_critical'] ?? false,
                ];
            }
        }

        // If parsing fails, return safe default
        Log::warning('Failed to parse DeepSeek response', ['response' => $response]);
        return $this->getSafeDefault($locale);
    }

    /**
     * Get safe default response when API fails
     *
     * @param string $locale
     * @return array
     */
    protected function getSafeDefault(string $locale): array
    {
        $messages = [
            'uz' => [
                'analysis' => 'Vital ko\'rsatkichlaringiz qayd etildi. Iltimos, shifokoringiz bilan maslahatlashing.',
                'nutrition' => 'Muvozanatli ovqatlanishda davom eting va ko\'p suv iching.',
            ],
            'ru' => [
                'analysis' => 'Ваши показатели зафиксированы. Пожалуйста, проконсультируйтесь с врачом.',
                'nutrition' => 'Продолжайте сбалансированное питание и пейте больше воды.',
            ],
            'en' => [
                'analysis' => 'Your vitals have been recorded. Please consult with your doctor.',
                'nutrition' => 'Continue balanced diet and drink plenty of water.',
            ],
        ];

        $message = $messages[$locale] ?? $messages['en'];

        return [
            'status' => 'yellow',
            'analysis_text' => $message['analysis'],
            'nutrition_advice' => $message['nutrition'],
            'is_critical' => false,
        ];
    }
}

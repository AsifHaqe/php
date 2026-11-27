<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiDoctorExplanationService
{
    /**
     * @param array<int, array<string, int|float|string|null>> $doctors
     * @return array{available: bool, message: string, reasons: array<int, string>}
     */
    public function explain(array $doctors): array
    {
        $apiKey = config('services.gemini.api_key');

        if (! is_string($apiKey) || $apiKey === '') {
            return [
                'available' => false,
                'message' => 'Gemini is not configured. Matches are still filtered and ranked using doctor records and distance.',
                'reasons' => [],
            ];
        }

        $model = config('services.gemini.model', 'gemini-3.5-flash-lite');
        $prompt = implode("\n", [
            'Explain these nearby doctor matches using only their names, approximate distances, and verified-review aggregates.',
            'The list is the complete allowed set. Do not add, remove, reorder, or invent doctors.',
            'Do not infer quality, credentials, treatment outcomes, diagnoses, or patient needs.',
            'Return JSON only, in this format: {"recommendations":[{"doctor_id":1,"reason":"..."}]}.',
            'Return one short reason for every supplied doctor, preserving their IDs. Reasons must be factual and under 140 characters.',
            'Doctor matches: '.json_encode($doctors, JSON_THROW_ON_ERROR),
        ]);

        try {
            $response = Http::acceptJson()
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->connectTimeout(3)
                ->timeout(10)
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/'.rawurlencode($model).':generateContent',
                    [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]],
                        ],
                        'generationConfig' => [
                            'responseMimeType' => 'application/json',
                            'maxOutputTokens' => 512,
                        ],
                    ],
                );
        } catch (ConnectionException $exception) {
            Log::warning('Gemini doctor explanation request failed to connect.', ['model' => $model]);

            return $this->unavailableMessage();
        }

        if (! $response->successful()) {
            Log::warning('Gemini doctor explanation request was rejected.', [
                'model' => $model,
                'status' => $response->status(),
            ]);

            return $this->unavailableMessage();
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
        $result = is_string($text) ? json_decode($text, true) : null;

        if (! is_array($result) || ! is_array($result['recommendations'] ?? null)) {
            Log::warning('Gemini returned an invalid doctor explanation response.', ['model' => $model]);

            return $this->unavailableMessage();
        }

        $expectedIds = array_map(static fn (array $doctor): int => (int) $doctor['id'], $doctors);
        $reasons = [];

        foreach ($result['recommendations'] as $recommendation) {
            if (
                ! is_array($recommendation)
                || ! isset($recommendation['doctor_id'], $recommendation['reason'])
                || ! is_string($recommendation['reason'])
                || ! in_array((int) $recommendation['doctor_id'], $expectedIds, true)
            ) {
                Log::warning('Gemini returned a doctor explanation outside the supplied candidate set.', ['model' => $model]);

                return $this->unavailableMessage();
            }

            $reason = trim($recommendation['reason']);
            if ($reason === '') {
                Log::warning('Gemini returned an empty doctor explanation.', ['model' => $model]);

                return $this->unavailableMessage();
            }

            $reasons[(int) $recommendation['doctor_id']] = mb_substr($reason, 0, 140);
        }

        if (count($reasons) !== count($expectedIds)) {
            Log::warning('Gemini did not explain every supplied doctor.', ['model' => $model]);

            return $this->unavailableMessage();
        }

        return [
            'available' => true,
            'message' => 'AI explanations use only the verified doctor information shown. Recommendations are not medical advice.',
            'reasons' => $reasons,
        ];
    }

    /**
     * @return array{available: false, message: string, reasons: array<int, string>}
     */
    private function unavailableMessage(): array
    {
        return [
            'available' => false,
            'message' => 'Gemini explanations are unavailable right now. The matches below are still filtered and ranked by distance and verified doctor data.',
            'reasons' => [],
        ];
    }
}

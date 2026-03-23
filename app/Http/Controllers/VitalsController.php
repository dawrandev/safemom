<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVitalsRequest;
use App\Jobs\NotifyDoctorCriticalVitals;
use App\Models\AiDiagnosis;
use App\Models\Vital;
use App\Services\DeepSeekService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VitalsController extends Controller
{
    /**
     * Store vitals and get AI analysis
     *
     * @param StoreVitalsRequest $request
     * @param DeepSeekService $deepSeekService
     * @return JsonResponse
     */
    public function store(StoreVitalsRequest $request, DeepSeekService $deepSeekService): JsonResponse
    {
        $user = $request->user();
        $locale = $request->input('locale')
            ?: app()->getLocale()
            ?: session('locale', 'en');

        try {
            $result = DB::transaction(function () use ($user, $request, $deepSeekService, $locale) {
                // Save vitals
                $vital = Vital::create([
                    'user_id' => $user->id,
                    'systolic_bp' => $request->systolic_bp,
                    'diastolic_bp' => $request->diastolic_bp,
                    'heart_rate' => $request->heart_rate,
                    'temperature' => $request->temperature,
                    'glucose_level' => $request->glucose_level,
                ]);

                // Get AI analysis
                $analysis = $deepSeekService->analyzeVitals($vital, $locale);

                // Save diagnosis
                $diagnosis = AiDiagnosis::create([
                    'user_id' => $user->id,
                    'vitals_id' => $vital->id,
                    'status' => $analysis['status'],
                    'analysis_text' => $analysis['analysis_text'],
                    'nutrition_advice' => $analysis['nutrition_advice'],
                    'is_critical' => $analysis['is_critical'],
                ]);

                return ['vital' => $vital, 'diagnosis' => $diagnosis];
            });

            // Notify doctor if critical
            if ($result['diagnosis']->is_critical) {
                NotifyDoctorCriticalVitals::dispatch($result['diagnosis']->id);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'vital_id' => $result['vital']->id,
                    'diagnosis_id' => $result['diagnosis']->id,
                    'status' => $result['diagnosis']->status,
                    'analysis_text' => $result['diagnosis']->analysis_text,
                    'nutrition_advice' => $result['diagnosis']->nutrition_advice,
                    'is_critical' => $result['diagnosis']->is_critical,
                    'created_at' => $result['diagnosis']->created_at,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Vitals analysis failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('vitals.error_analyzing'),
            ], 500);
        }
    }
}

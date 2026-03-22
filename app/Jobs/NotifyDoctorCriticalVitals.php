<?php

namespace App\Jobs;

use App\Models\AiDiagnosis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class NotifyDoctorCriticalVitals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $diagnosisId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $diagnosis = AiDiagnosis::with(['user.doctor', 'vital'])->find($this->diagnosisId);

        if (!$diagnosis || !$diagnosis->is_critical) {
            return;
        }

        $patient = $diagnosis->user;
        $doctor = $patient->doctor;

        if (!$doctor || !$doctor->telegram_id) {
            Log::warning("No doctor assigned for patient {$patient->id}");
            return;
        }

        $vital = $diagnosis->vital;
        $locale = session('locale', 'en');

        $messages = [
            'uz' => "🚨 <b>Favqulodda: Kritik Vital Ko'rsatkichlar</b>\n\n<b>Bemor:</b> {$patient->name} {$patient->surname}\n<b>Telefon:</b> {$patient->phone}\n\n<b>Vital Ko'rsatkichlar:</b>\n• Qon Bosimi: {$vital->systolic_bp}/{$vital->diastolic_bp} mmHg\n• Yurak Urishi: {$vital->heart_rate} BPM\n• Harorat: {$vital->temperature}°F\n\n<b>AI Tahlil:</b>\n{$diagnosis->analysis_text}",

            'ru' => "🚨 <b>Срочно: Критические Показатели</b>\n\n<b>Пациент:</b> {$patient->name} {$patient->surname}\n<b>Телефон:</b> {$patient->phone}\n\n<b>Показатели:</b>\n• Давление: {$vital->systolic_bp}/{$vital->diastolic_bp} mmHg\n• Пульс: {$vital->heart_rate} BPM\n• Температура: {$vital->temperature}°F\n\n<b>Анализ ИИ:</b>\n{$diagnosis->analysis_text}",

            'en' => "🚨 <b>Urgent: Critical Vital Signs</b>\n\n<b>Patient:</b> {$patient->name} {$patient->surname}\n<b>Phone:</b> {$patient->phone}\n\n<b>Vital Signs:</b>\n• Blood Pressure: {$vital->systolic_bp}/{$vital->diastolic_bp} mmHg\n• Heart Rate: {$vital->heart_rate} BPM\n• Temperature: {$vital->temperature}°F\n\n<b>AI Analysis:</b>\n{$diagnosis->analysis_text}",
        ];

        try {
            Telegram::sendMessage([
                'chat_id' => $doctor->telegram_id,
                'text' => $messages[$locale] ?? $messages['en'],
                'parse_mode' => 'HTML',
            ]);

            Log::info("Critical vitals notification sent to doctor {$doctor->id} for patient {$patient->id}");
        } catch (\Exception $e) {
            Log::error("Failed to notify doctor {$doctor->id}", [
                'error' => $e->getMessage(),
                'patient_id' => $patient->id,
                'diagnosis_id' => $diagnosis->id,
            ]);
        }
    }
}

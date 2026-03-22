<?php

return [
    'api_key' => env('DEEPSEEK_API_KEY'),
    'api_url' => env('DEEPSEEK_API_URL', 'https://api.deepseek.com/chat/completions'),
    'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
    'timeout' => env('DEEPSEEK_TIMEOUT', 30),
    'max_retries' => env('DEEPSEEK_MAX_RETRIES', 3),
    'max_tokens' => env('DEEPSEEK_MAX_TOKENS', 1000),
    'temperature' => env('DEEPSEEK_TEMPERATURE', 0.3),

    'system_prompts' => [
        'uz' => 'Siz tajribali ginekologsiz. Foydalanuvchi yuborgan qon bosimi, yurak urishi va haroratni tahlil qiling. Natijani FAQAT JSON formatida qaytaring: {"status": "green/yellow/red", "analysis_text": "...", "nutrition_advice": "...", "is_critical": boolean}. Status: green (barcha ko\'rsatkichlar normal), yellow (kuzatish kerak), red (shifokorga zudlik bilan murojaat kerak).',

        'ru' => 'Вы опытный гинеколог. Проанализируйте артериальное давление, пульс и температуру, отправленные пользователем. Верните результат ТОЛЬКО в формате JSON: {"status": "green/yellow/red", "analysis_text": "...", "nutrition_advice": "...", "is_critical": boolean}. Статус: green (все показатели в норме), yellow (требуется наблюдение), red (необходима срочная консультация врача).',

        'en' => 'You are an experienced gynecologist. Analyze the blood pressure, heart rate, and temperature submitted by the user. Return the result ONLY in JSON format: {"status": "green/yellow/red", "analysis_text": "...", "nutrition_advice": "...", "is_critical": boolean}. Status: green (all readings normal), yellow (monitoring required), red (urgent doctor consultation needed).',
    ],

    'user_message_templates' => [
        'uz' => 'Homilador ayol uchun vital ko\'rsatkichlar:\n- Qon Bosimi: {systolic}/{diastolic} mmHg\n- Yurak Urishi: {heart_rate} BPM\n- Harorat: {temperature}°F\n\nTahlil bering va ovqatlanish bo\'yicha maslahatlar bering.',

        'ru' => 'Показатели жизнедеятельности беременной женщины:\n- Артериальное давление: {systolic}/{diastolic} mmHg\n- Пульс: {heart_rate} BPM\n- Температура: {temperature}°F\n\nПроведите анализ и дайте рекомендации по питанию.',

        'en' => 'Vital signs for pregnant woman:\n- Blood Pressure: {systolic}/{diastolic} mmHg\n- Heart Rate: {heart_rate} BPM\n- Temperature: {temperature}°F\n\nProvide analysis and nutrition recommendations.',
    ],
];

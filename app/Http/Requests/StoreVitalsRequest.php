<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitalsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'systolic_bp' => 'required|integer|min:60|max:250',
            'diastolic_bp' => 'required|integer|min:30|max:150',
            'heart_rate' => 'required|integer|min:40|max:200',
            'temperature' => 'required|numeric|min:95.0|max:110.0',
            'glucose_level' => 'nullable|numeric|min:40.0|max:400.0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'systolic_bp.required' => 'Systolic blood pressure is required.',
            'systolic_bp.integer' => 'Systolic blood pressure must be a number.',
            'systolic_bp.min' => 'Systolic blood pressure must be at least 60.',
            'systolic_bp.max' => 'Systolic blood pressure must not exceed 250.',
            'diastolic_bp.required' => 'Diastolic blood pressure is required.',
            'diastolic_bp.integer' => 'Diastolic blood pressure must be a number.',
            'diastolic_bp.min' => 'Diastolic blood pressure must be at least 30.',
            'diastolic_bp.max' => 'Diastolic blood pressure must not exceed 150.',
            'heart_rate.required' => 'Heart rate is required.',
            'heart_rate.integer' => 'Heart rate must be a number.',
            'heart_rate.min' => 'Heart rate must be at least 40.',
            'heart_rate.max' => 'Heart rate must not exceed 200.',
            'temperature.required' => 'Temperature is required.',
            'temperature.numeric' => 'Temperature must be a number.',
            'temperature.min' => 'Temperature must be at least 95.0°F.',
            'temperature.max' => 'Temperature must not exceed 110.0°F.',
            'glucose_level.numeric' => 'Glucose level must be a number.',
            'glucose_level.min' => 'Glucose level must be at least 40.0.',
            'glucose_level.max' => 'Glucose level must not exceed 400.0.',
        ];
    }
}

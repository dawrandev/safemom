<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'age' => 'required|integer|min:14|max:55',
            'lmp_date' => 'required|date|before:today',
            'height' => 'required|integer|min:100|max:220',
            'weight' => 'required|numeric|min:30|max:200',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-,unknown',
        ];
    }
}

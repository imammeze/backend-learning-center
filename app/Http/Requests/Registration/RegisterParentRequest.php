<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterParentRequest extends FormRequest
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
            // Parent Data
            'parent_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'whatsapp_number' => 'required|string|max:20',
            
            // Student Data
            'student_email' => 'required|string|email|max:255|unique:users,email',
            'student_full_name' => 'required|string|max:255',
            'student_nickname' => 'nullable|string|max:255',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['L', 'P'])],
            'birth_order' => 'nullable|integer',
            'sibling_count' => 'nullable|integer',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
            
            // Program Data
            'program_code' => 'required|exists:programs,code',
            'notes' => 'nullable|string',
        ];
    }
}

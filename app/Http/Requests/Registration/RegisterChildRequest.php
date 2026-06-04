<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class RegisterChildRequest extends FormRequest
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
            // Data Program
            'program_code' => ['required', 'string', 'exists:programs,code'],
            'notes' => ['nullable', 'string', 'max:500'],

            // Data Anak (Siswa)
            'student_full_name' => ['required', 'string', 'max:255'],
            'student_nickname' => ['nullable', 'string', 'max:255'],
            'student_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            
            // Detail Anak Tambahan
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:L,P'],
            'birth_order' => ['nullable', 'integer', 'min:1'],
            'sibling_count' => ['nullable', 'integer', 'min:0'],
            'address' => ['nullable', 'string', 'max:1000'],
            'medical_history' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

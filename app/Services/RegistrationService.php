<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\StudentAccountCreated;
use Exception;

class RegistrationService
{
    /**
     * Register a parent and their student.
     *
     * @param array $data Validated data
     * @return array
     * @throws Exception
     */
    public function registerParent(array $data)
    {
        $program = Program::where('code', $data['program_code'])->first();

        try {
            DB::beginTransaction();

            // 1. Create Parent User
            $parent = User::create([
                'name' => $data['parent_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'whatsapp_number' => $data['whatsapp_number'],
            ]);
            
            // Assign Spatie Role
            $parent->assignRole('orang_tua');

            // 2. Create Student User Account
            $studentPassword = Str::random(10);
            $studentUser = User::create([
                'name' => $data['student_nickname'] ?? $data['student_full_name'],
                'email' => $data['student_email'],
                'password' => Hash::make($studentPassword),
                'whatsapp_number' => null,
            ]);
            $studentUser->assignRole('siswa_mandiri');

            // 3. Create Student
            $student = Student::create([
                'user_id' => $studentUser->id,
                'parent_id' => $parent->id,
                'full_name' => $data['student_full_name'],
                'nickname' => $data['student_nickname'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'gender' => $data['gender'] ?? null,
                'birth_order' => $data['birth_order'] ?? null,
                'sibling_count' => $data['sibling_count'] ?? null,
                'address' => $data['address'] ?? null,
                'medical_history' => $data['medical_history'] ?? null,
            ]);

            // 4. Create Registration
            $registration = Registration::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();

            // 5. Send Email to Parent
            Mail::to($parent->email)->send(new StudentAccountCreated($studentUser, $studentPassword));

            // 6. Generate Sanctum Token
            $token = $parent->createToken('auth-token')->plainTextToken;

            return [
                'token' => $token,
                'user' => [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'email' => $parent->email,
                    'whatsapp_number' => $parent->whatsapp_number,
                    'role' => 'orang_tua',
                ],
                'data' => [
                    'parent' => $parent,
                    'student' => $student,
                    'registration' => $registration
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Register a single student (independent).
     *
     * @param array $data Validated data
     * @return array
     * @throws Exception
     */
    public function registerStudent(array $data)
    {
        $program = Program::where('code', $data['program_code'])->first();

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'whatsapp_number' => $data['whatsapp_number'] ?? null,
            ]);
            
            $user->assignRole('siswa_mandiri');

            $student = Student::create([
                'user_id' => $user->id,
                'full_name' => $data['full_name'],
                'nickname' => $data['nickname'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'gender' => $data['gender'] ?? null,
                'birth_order' => $data['birth_order'] ?? null,
                'sibling_count' => $data['sibling_count'] ?? null,
                'address' => $data['address'] ?? null,
                'medical_history' => $data['medical_history'] ?? null,
            ]);

            $registration = Registration::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();

            $token = $user->createToken('auth-token')->plainTextToken;

            return [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp_number' => $user->whatsapp_number,
                    'role' => 'siswa_mandiri',
                ],
                'data' => [
                    'user' => $user,
                    'student' => $student,
                    'registration' => $registration
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

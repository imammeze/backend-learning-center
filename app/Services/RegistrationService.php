<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use App\Models\Program;
use App\Models\ProgramClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\StudentAccountCreated;
use Exception;
use Carbon\Carbon;

class RegistrationService
{
    public function registerParent(array $data)
    {
        $program = Program::where('code', $data['program_code'])->first();

        try {
            DB::beginTransaction();

            $parent = User::create([
                'name' => $data['parent_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'whatsapp_number' => $data['whatsapp_number'],
            ]);
            
            $parent->assignRole('orang_tua');
            $studentPassword = Str::random(10);
            $studentUser = User::create([
                'name' => $data['student_nickname'] ?? $data['student_full_name'],
                'email' => $data['student_email'],
                'password' => Hash::make($studentPassword),
                'whatsapp_number' => null,
            ]);
            $studentUser->assignRole('siswa_mandiri');

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

            $programClassId = $this->determineProgramClass($program->id, $data['birth_date'] ?? null);
            $registration = Registration::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'program_class_id' => $programClassId,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();
            Mail::to($parent->email)->send(new StudentAccountCreated($studentUser, $studentPassword));
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

            $programClassId = $this->determineProgramClass($program->id, $data['birth_date'] ?? null);
            $registration = Registration::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'program_class_id' => $programClassId,
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

    public function registerNewChild($parentId, array $data)
    {
        $program = Program::where('code', $data['program_code'])->first();
        $parent = User::findOrFail($parentId);

        try {
            DB::beginTransaction();

            $studentPassword = Str::random(10);
            $studentUser = User::create([
                'name' => $data['student_nickname'] ?? $data['student_full_name'],
                'email' => $data['student_email'],
                'password' => Hash::make($studentPassword),
                'whatsapp_number' => null,
            ]);
            $studentUser->assignRole('siswa_mandiri');

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

            $programClassId = $this->determineProgramClass($program->id, $data['birth_date'] ?? null);
            $registration = Registration::create([
                'student_id' => $student->id,
                'program_id' => $program->id,
                'program_class_id' => $programClassId,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();

            Mail::to($parent->email)->send(new StudentAccountCreated($studentUser, $studentPassword));

            return [
                'data' => [
                    'student' => $student,
                    'registration' => $registration
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function determineProgramClass($programId, $birthDate)
    {
        if (!$birthDate) return null;

        try {
            $age = Carbon::parse($birthDate)->age;

            $programClass = ProgramClass::where('program_id', $programId)
                ->where('min_age', '<=', $age)
                ->where('max_age', '>=', $age)
                ->first();

            return $programClass ? $programClass->id : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

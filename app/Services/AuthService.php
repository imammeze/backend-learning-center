<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Handle user login logic.
     */
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if (! $user->hasAnyRole(['orang_tua', 'siswa_mandiri'])) {
            throw ValidationException::withMessages([
                'email' => ['Akun ini tidak memiliki akses ke dashboard. Silakan gunakan halaman admin.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;
        $role = $user->getRoleNames()->first();

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'whatsapp_number' => $user->whatsapp_number,
                'role' => $role,
            ]
        ];
    }

    /**
     * Handle user logout logic.
     */
    public function logout(User $user)
    {
        $user->currentAccessToken()->delete();
        return true;
    }

    /**
     * Get user profile details.
     */
    public function getProfile(User $user)
    {
        $role = $user->getRoleNames()->first();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'whatsapp_number' => $user->whatsapp_number,
            'role' => $role,
        ];

        if ($role === 'orang_tua') {
            $data['children'] = $user->students()->with('registrations.program')->get();
        }
        
        if ($role === 'siswa_mandiri') {
            $student = \App\Models\Student::where('user_id', $user->id)->with('registrations.program')->first();
            $data['student'] = $student;
        }

        return $data;
    }
}

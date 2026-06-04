<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\RegisterParentRequest;
use App\Http\Requests\Registration\RegisterStudentRequest;
use App\Services\RegistrationService;

class RegistrationController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function registerParent(RegisterParentRequest $request)
    {
        try {
            $result = $this->registrationService->registerParent($request->validated());

            return response()->json([
                'message' => 'Registrasi Orang Tua dan Siswa berhasil.',
                'token' => $result['token'],
                'user' => $result['user'],
                'data' => $result['data']
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat registrasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerStudent(RegisterStudentRequest $request)
    {
        try {
            $result = $this->registrationService->registerStudent($request->validated());

            return response()->json([
                'message' => 'Registrasi Siswa Mandiri berhasil.',
                'token' => $result['token'],
                'user' => $result['user'],
                'data' => $result['data']
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat registrasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function profile(Request $request)
    {
        $student = Student::where('user_id', $request->user()->id)
            ->with('registrations.program')
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Data profil siswa tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => $student,
        ]);
    }

    public function modules(Request $request)
    {
        return response()->json([
            'data' => [],
            'message' => 'Fitur modul pembelajaran akan segera tersedia.',
        ]);
    }

    public function registrations(Request $request)
    {
        $student = Student::where('user_id', $request->user()->id)->first();

        if (! $student) {
            return response()->json([
                'data' => [],
            ]);
        }

        $registrations = $student->registrations()->with('program')->get();

        return response()->json([
            'data' => $registrations,
        ]);
    }
}

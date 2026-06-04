<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\Registration\RegisterChildRequest;
use App\Services\RegistrationService;

class ParentDashboardController extends Controller
{
    public function children(Request $request)
    {
        $children = Student::where('parent_id', $request->user()->id)
            ->with('registrations.program')
            ->get();

        return response()->json([
            'data' => $children,
        ]);
    }

    public function registrations(Request $request, Student $student)
    {
        if ($student->parent_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $registrations = $student->registrations()->with('program')->get();

        return response()->json([
            'data' => $registrations,
        ]);
    }

    public function grades(Request $request, Student $student)
    {
        if ($student->parent_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return response()->json([
            'data' => [],
            'message' => 'Fitur nilai akan segera tersedia.',
        ]);
    }

    public function schedules(Request $request, Student $student)
    {
        if ($student->parent_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return response()->json([
            'data' => [],
            'message' => 'Fitur jadwal akan segera tersedia.',
        ]);
    }

    public function registerChild(RegisterChildRequest $request, RegistrationService $registrationService)
    {
        try {
            $result = $registrationService->registerNewChild($request->user()->id, $request->validated());

            return response()->json([
                'message' => 'Anak berhasil didaftarkan.',
                'data' => $result['data']
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mendaftarkan anak.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

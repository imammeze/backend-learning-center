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
        $student = Student::where('user_id', $request->user()->id)->first();
        if (!$student) {
            return response()->json(['data' => []]);
        }

        $registrations = $student->registrations()->where('status', 'approved')->get();
        if ($registrations->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'Pendaftaran Anda belum disetujui atau belum ada.']);
        }

        $modules = \App\Models\LearningModule::where(function ($query) use ($registrations) {
            foreach ($registrations as $reg) {
                $query->orWhere(function ($q) use ($reg) {
                    $q->where('program_id', $reg->program_id)
                      ->where(function ($sq) use ($reg) {
                          $sq->whereNull('program_class_id')
                             ->orWhere('program_class_id', $reg->program_class_id);
                      });
                });
            }
        })
        ->with(['program', 'programClass', 'teacher'])
        ->orderBy('meeting_number', 'asc')
        ->get();

        $modules->transform(function ($module) {
            if ($module->file_path) {
                $module->file_url = url('storage/' . $module->file_path);
            } else {
                $module->file_url = null;
            }
            return $module;
        });

        return response()->json([
            'data' => $modules,
            'message' => $modules->isEmpty() ? 'Belum ada modul untuk kelas Anda saat ini.' : 'Berhasil mengambil modul.'
        ]);
    }

    public function download(\App\Models\LearningModule $module, Request $request)
    {
        // Pastikan student ini berhak download modul tersebut (Opsional: tambahkan validasi)
        if (!$module->file_path) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }

        $filePath = storage_path('app/public/' . $module->file_path);
        
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File fisik tidak ditemukan'], 404);
        }

        return response()->download($filePath, $module->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
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

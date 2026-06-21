<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with('user:id,name,email,role')
            ->when($request->user()->role === 'piket', fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest('check_in')
            ->paginate((int) $request->query('per_page', 10));

        return response()->json($attendances);
    }

    public function checkIn(Request $request)
    {
        $openAttendance = Attendance::where('user_id', $request->user()->id)->whereNull('check_out')->first();

        if ($openAttendance) {
            throw ValidationException::withMessages([
                'attendance' => ['Masih ada sesi absensi yang belum check out.'],
            ]);
        }

        return response()->json(Attendance::create([
            'user_id' => $request->user()->id,
            'check_in' => now(),
        ]), 201);
    }

    public function checkOut(Request $request)
    {
        $attendance = Attendance::where('user_id', $request->user()->id)->whereNull('check_out')->latest('check_in')->first();

        if (! $attendance) {
            throw ValidationException::withMessages([
                'attendance' => ['Belum ada sesi check in aktif.'],
            ]);
        }

        $attendance->update(['check_out' => now()]);

        return response()->json($attendance->fresh());
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $schedules = Schedule::with('user:id,name,email,role')
            ->when($request->user()->role === 'piket', fn ($query) => $query->where('user_id', $request->user()->id))
            ->when($request->query('from'), fn ($query, $from) => $query->whereDate('date', '>=', $from))
            ->when($request->query('to'), fn ($query, $to) => $query->whereDate('date', '<=', $to))
            ->orderBy('date')
            ->paginate((int) $request->query('per_page', 10));

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        return response()->json(Schedule::create($this->validateSchedule($request)), 201);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update($this->validateSchedule($request));

        return response()->json($schedule->load('user:id,name,email,role'));
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }

    private function validateSchedule(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'shift' => ['required', 'string', 'max:255'],
        ]);
    }
}

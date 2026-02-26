<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Standard Philippine Government Office Hours: 8:00 AM - 12:00 PM, 1:00 PM - 5:00 PM
     */
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $date,
        ]);

        if ($now->format('A') === 'AM') {
            if ($attendance->time_in_am) {
                return redirect()->back()->with('error', 'Already clocked in for AM.');
            }
            $attendance->time_in_am = $time;

            // Calculate late (Standard 8:00 AM)
            $standardIn = Carbon::createFromFormat('H:i:s', '08:00:00');
            if ($now->greaterThan($standardIn)) {
                $attendance->late_minutes += $now->diffInMinutes($standardIn);
            }
        } else {
            if ($attendance->time_in_pm) {
                return redirect()->back()->with('error', 'Already clocked in for PM.');
            }
            $attendance->time_in_pm = $time;

            // Calculate late (Standard 1:00 PM)
            $standardIn = Carbon::createFromFormat('H:i:s', '13:00:00');
            if ($now->greaterThan($standardIn)) {
                $attendance->late_minutes += $now->diffInMinutes($standardIn);
            }
        }

        $attendance->status = 'present';
        $attendance->ip_address = $request->ip();
        $attendance->save();

        return redirect()->back()->with('success', 'Clocked in successfully at '.$now->format('h:i A'));
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        if (! $attendance) {
            return redirect()->back()->with('error', 'No clock-in record found for today.');
        }

        if ($now->format('A') === 'AM') {
            if ($attendance->time_out_am) {
                return redirect()->back()->with('error', 'Already clocked out for AM.');
            }
            $attendance->time_out_am = $time;

            // Calculate undertime (Standard 12:00 PM)
            $standardOut = Carbon::createFromFormat('H:i:s', '12:00:00');
            if ($now->lessThan($standardOut)) {
                $attendance->undertime_minutes += $now->diffInMinutes($standardOut);
            }
        } else {
            if ($attendance->time_out_pm) {
                return redirect()->back()->with('error', 'Already clocked out for PM.');
            }
            $attendance->time_out_pm = $time;

            // Calculate undertime (Standard 5:00 PM)
            $standardOut = Carbon::createFromFormat('H:i:s', '17:00:00');
            if ($now->lessThan($standardOut)) {
                $attendance->undertime_minutes += $now->diffInMinutes($standardOut);
            }
        }

        $attendance->save();

        return redirect()->back()->with('success', 'Clocked out successfully at '.$now->format('h:i A'));
    }

    public function index()
    {
        $attendances = Auth::user()->attendances()
            ->orderBy('date', 'desc')
            ->paginate(31);

        return Inertia::render('Employee/Attendance/Index', [
            'attendances' => $attendances,
        ]);
    }
}

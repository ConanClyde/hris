<?php

namespace App\Features\Calendar\Http\Controllers\Employee;

use App\Features\Calendar\Http\GoogleCalendarService;
use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CalendarController extends Controller
{
    use GoogleCalendarService;

    public function index()
    {
        return Inertia::render('Employee/Calendar/Index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $category = $request->query('category', 'all');
        $status = $request->query('status', 'all');

        $events = [];

        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $holidays = $this->getGoogleCalendarHolidays($start, $end);

        // Custom Holidays
        $customHolidaysQuery = CustomHoliday::query();
        $customHolidaysQuery->where(function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start, $end])
                ->orWhere('is_recurring', true);
        });
        $customEvents = $this->buildCustomHolidayEvents($customHolidaysQuery->get(), $start, $end);

        // Employee's own leave
        if ($employeeId && ($category === 'all' || $category === 'leave')) {
            $leaveQuery = LeaveApplication::query()
                ->where('employee_id', $employeeId);

            if ($start && $end) {
                $leaveQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $leaveQuery->where('status', (string) $status);
            }

            $leaves = $leaveQuery->get();
            // For employee view, title doesn't include their own name
            foreach ($leaves as $leave) {
                $events[] = [
                    'id' => 'leave-'.$leave->id,
                    'title' => $leave->type ?? 'Leave',
                    'start' => optional($leave->date_from)?->toDateString(),
                    'end' => $leave->date_to ? $leave->date_to->toDateString() : null,
                    'category' => 'leave',
                    'status' => $leave->status,
                    'description' => $leave->reason ?? '',
                    'textColor' => '#ffffff',
                    'backgroundColor' => $leave->status === 'approved' ? '#10B981' : ($leave->status === 'rejected' ? '#EF4444' : '#F59E0B'),
                    'borderColor' => $leave->status === 'approved' ? '#059669' : ($leave->status === 'rejected' ? '#DC2626' : '#D97706'),
                    'allDay' => true,
                    'extendedProps' => [
                        'type' => 'leave_application',
                        'employee_id' => $leave->employee_id,
                    ],
                ];
            }
        }

        // Employee's own training
        if ($employeeId && ($category === 'all' || $category === 'training')) {
            $trainingQuery = Training::query()
                ->where('employee_id', $employeeId);

            if ($start && $end) {
                $trainingQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $trainingQuery->where('status', (string) $status);
            }

            foreach ($trainingQuery->get() as $training) {
                $events[] = [
                    'id' => 'training-'.$training->id,
                    'title' => $training->title ?? 'Training',
                    'start' => optional($training->date_from)?->toDateString(),
                    'end' => $training->date_to ? $training->date_to->toDateString() : null,
                    'category' => 'training',
                    'status' => $training->status,
                    'description' => $training->provider ?? '',
                    'textColor' => '#ffffff',
                    'backgroundColor' => $training->status === 'approved' ? '#2563EB' : ($training->status === 'rejected' ? '#EF4444' : '#38BDF8'),
                    'borderColor' => $training->status === 'approved' ? '#1D4ED8' : ($training->status === 'rejected' ? '#DC2626' : '#0EA5E9'),
                    'allDay' => true,
                    'extendedProps' => [
                        'type' => 'training',
                        'employee_id' => $training->employee_id,
                    ],
                ];
            }
        }

        $events = array_merge($events, $holidays, $customEvents);

        // Filter by category
        $filtered = array_filter($events, function ($e) use ($category, $status) {
            if ($category !== 'all' && $e['category'] !== $category) {
                return false;
            }
            if ($category === 'leave' && $status !== 'all' && $e['status'] !== $status) {
                return false;
            }

            return true;
        });

        return response()->json(array_values($filtered));
    }
}

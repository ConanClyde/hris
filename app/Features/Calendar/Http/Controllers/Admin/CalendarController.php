<?php

namespace App\Features\Calendar\Http\Controllers\Admin;

use App\Features\Calendar\Http\GoogleCalendarService;
use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    use GoogleCalendarService;

    public function index()
    {
        $customHolidays = CustomHoliday::orderBy('date', 'desc')->get();

        return Inertia::render('Admin/Calendar/Index', [
            'customHolidays' => $customHolidays,
        ]);
    }

    public function events(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $category = $request->input('category', 'all');
        $status = $request->input('status', 'all');

        // Fetch Holidays from Google Calendar (or fallback)
        $holidays = $this->getGoogleCalendarHolidays($start, $end);

        // Fetch Custom Holidays
        $customHolidaysQuery = CustomHoliday::query();
        $customHolidaysQuery->where(function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start, $end])
                ->orWhere('is_recurring', true);
        });
        $customEvents = $this->buildCustomHolidayEvents($customHolidaysQuery->get(), $start, $end);

        $leaveEvents = [];
        $trainingEvents = [];

        if ($category === 'all' || $category === 'leave') {
            $leaveQuery = LeaveApplication::query();
            if ($start && $end) {
                $leaveQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $leaveQuery->where('status', (string) $status);
            }
            $leaveEvents = $this->buildLeaveEvents($leaveQuery->get());
        }

        if ($category === 'all' || $category === 'training') {
            $trainingQuery = Training::query();
            if ($start && $end) {
                $trainingQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $trainingQuery->where('status', (string) $status);
            }
            $trainingEvents = $this->buildTrainingEvents($trainingQuery->get());
        }

        $events = array_merge($holidays, $customEvents, $leaveEvents, $trainingEvents);

        if ($category && $category !== 'all') {
            $events = array_values(array_filter($events, fn ($e) => ($e['category'] ?? null) === $category));
        }

        return response()->json(array_values($events));
    }
}

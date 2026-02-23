<?php

namespace App\Features\Calendar\Http\Controllers\HR;

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
        return Inertia::render('HR/Calendar/Index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $category = $request->query('category', 'all');
        $status = $request->query('status', 'all');

        $events = [];

        $holidays = $this->getGoogleCalendarHolidays($start, $end);

        // Custom Holidays
        $customHolidaysQuery = CustomHoliday::query();
        $customHolidaysQuery->where(function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start, $end])
                ->orWhere('is_recurring', true);
        });
        $customEvents = $this->buildCustomHolidayEvents($customHolidaysQuery->get(), $start, $end);

        // Leave
        if ($category === 'all' || $category === 'leave') {
            $leaveQuery = LeaveApplication::query();
            if ($start && $end) {
                $leaveQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $leaveQuery->where('status', (string) $status);
            }
            $events = array_merge($events, $this->buildLeaveEvents($leaveQuery->get()));
        }

        // Training
        if ($category === 'all' || $category === 'training') {
            $trainingQuery = Training::query();
            if ($start && $end) {
                $trainingQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $trainingQuery->where('status', (string) $status);
            }
            $events = array_merge($events, $this->buildTrainingEvents($trainingQuery->get()));
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

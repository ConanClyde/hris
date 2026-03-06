<?php

namespace App\Features\Calendar\Http\Controllers\Admin;

use App\Features\Calendar\Http\GoogleCalendarService;
use App\Features\Calendar\Models\CustomHoliday;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    use GoogleCalendarService;

    public function index(): Response
    {
        $customHolidays = CustomHoliday::orderBy('date', 'desc')->get();

        return Inertia::render('Admin/Calendar/Index', [
            'holidays' => $customHolidays,
        ]);
    }

    public function events(Request $request)
    {
        $validated = $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ]);

        $start = (string) $validated['start'];
        $end = (string) $validated['end'];

        // Fetch Holidays from Google Calendar (or fallback)
        $holidays = $this->getGoogleCalendarHolidays($start, $end);

        // Fetch Custom Holidays
        $customHolidaysQuery = CustomHoliday::query();
        $customHolidaysQuery->where(function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start, $end])
                ->orWhere('is_recurring', true);
        });
        $customEvents = $this->buildCustomHolidayEvents($customHolidaysQuery->get(), $start, $end);

        $events = array_merge($holidays, $customEvents);

        return response()->json(array_values($events));
    }
}

<?php

namespace App\Features\Calendar\Http\Controllers\Admin;

use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $customHolidays = CustomHoliday::orderBy('date', 'desc')->get();

        return view('features.calendar.admin.index', compact('customHolidays'));
    }

    public function events(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $category = $request->input('category'); // 'all', 'leave', 'training', 'holiday'
        $status = $request->input('status');     // 'all', 'approved', 'pending'

        // Fetch Holidays from Google Calendar (or fallback)
        $holidays = $this->getGoogleCalendarHolidays($start, $end);

        // Fetch Custom Holidays
        $customHolidaysQuery = CustomHoliday::query();
        $customHolidaysQuery->where(function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start, $end])
                ->orWhere('is_recurring', true);
        });

        $customHolidays = $customHolidaysQuery->get();
        $customEvents = [];

        foreach ($customHolidays as $holiday) {
            $datesToRender = [];

            if ($holiday->is_recurring) {
                $startYear = date('Y', strtotime($start));
                $endYear = date('Y', strtotime($end));

                for ($y = $startYear; $y <= $endYear; $y++) {
                    $datesToRender[] = $y.'-'.$holiday->date->format('m-d');
                }
            } else {
                $datesToRender[] = $holiday->date->format('Y-m-d');
            }

            foreach ($datesToRender as $dateStr) {
                if ($dateStr < $start || $dateStr >= $end) {
                    continue;
                }

                $customEvents[] = [
                    'id' => 'custom-'.$holiday->id.'-'.$dateStr,
                    'title' => $holiday->title,
                    'start' => $dateStr,
                    'category' => 'holiday',
                    'status' => 'approved',
                    'description' => $holiday->description ?? $holiday->title,
                    'textColor' => '#ffffff',
                    'backgroundColor' => '#EF4444', // Red 500
                    'borderColor' => '#DC2626', // Red 600
                    'allDay' => true,
                    'extendedProps' => [
                        'type' => 'custom_holiday',
                        'category_label' => $holiday->category,
                    ],
                ];
            }
        }

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

            foreach ($leaveQuery->get() as $leave) {
                $leaveEvents[] = [
                    'id' => 'leave-'.$leave->id,
                    'title' => trim(($leave->employee_name ? $leave->employee_name.': ' : '').($leave->type ?? 'Leave')),
                    'start' => optional($leave->date_from)->toDateString(),
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

        if ($category === 'all' || $category === 'training') {
            $trainingQuery = Training::query();
            if ($start && $end) {
                $trainingQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $trainingQuery->where('status', (string) $status);
            }

            foreach ($trainingQuery->get() as $training) {
                $trainingEvents[] = [
                    'id' => 'training-'.$training->id,
                    'title' => trim(($training->employee_name ? $training->employee_name.': ' : '').($training->title ?? 'Training')),
                    'start' => optional($training->date_from)->toDateString(),
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

        $events = array_merge($holidays, $customEvents, $leaveEvents, $trainingEvents);

        if ($category && $category !== 'all') {
            $events = array_values(array_filter($events, fn ($e) => ($e['category'] ?? null) === $category));
        }

        return response()->json(array_values($events));
    }

    private function getGoogleCalendarHolidays($startStr, $endStr)
    {
        $apiKey = config('services.google.calendar_api_key');

        if (! $apiKey) {
            return [];
        }

        $cacheKey = "google_holidays_{$startStr}_{$endStr}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 60 * 24, function () use ($apiKey, $startStr, $endStr) {
            $calendarId = 'en.philippines#holiday@group.v.calendar.google.com';
            $url = 'https://www.googleapis.com/calendar/v3/calendars/'.urlencode($calendarId).'/events';

            try {
                $timeMin = (new \DateTime($startStr))->format(\DateTime::RFC3339);
                $timeMax = (new \DateTime($endStr))->format(\DateTime::RFC3339);

                $response = \Illuminate\Support\Facades\Http::get($url, [
                    'key' => $apiKey,
                    'timeMin' => $timeMin,
                    'timeMax' => $timeMax,
                    'singleEvents' => 'true',
                    'orderBy' => 'startTime',
                ]);

                if ($response->failed()) {
                    \Illuminate\Support\Facades\Log::error('Google Calendar API Error: '.$response->body());

                    return [];
                }

                $data = $response->json();
                $holidays = [];

                if (! empty($data['items'])) {
                    foreach ($data['items'] as $item) {
                        $start = $item['start']['date'] ?? $item['start']['dateTime'] ?? null;
                        $end = $item['end']['date'] ?? $item['end']['dateTime'] ?? null;

                        $holidays[] = [
                            'id' => $item['id'],
                            'title' => $item['summary'],
                            'start' => $start,
                            'end' => $end,
                            'category' => 'holiday',
                            'status' => 'approved',
                            'description' => $item['description'] ?? 'Public Holiday',
                            'textColor' => '#ffffff',
                            'backgroundColor' => '#EF4444',
                            'borderColor' => '#DC2626',
                            'allDay' => true,
                        ];
                    }
                }

                return $holidays;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Google Calendar Exception: '.$e->getMessage());

                return [];
            }
        });
    }
}

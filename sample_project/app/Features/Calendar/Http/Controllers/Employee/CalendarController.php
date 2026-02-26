<?php

namespace App\Features\Calendar\Http\Controllers\Employee;

use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        return view('features.calendar.employee.index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $category = $request->query('category', 'all');
        $status = $request->query('status', 'all');

        $events = [];

        $employeeMap = [
            'employee01' => 'EMP-001',
        ];

        $userId = (string) Auth::id();
        $employeeId = $employeeMap[$userId] ?? null;

        $holidays = $this->getGoogleCalendarHolidays($start, $end);

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
                    'backgroundColor' => '#EF4444',
                    'borderColor' => '#DC2626',
                    'allDay' => true,
                ];
            }
        }

        if (empty($holidays)) {
            $holidays = [
                [
                    'id' => 3,
                    'title' => 'Public Holiday: Sample Day',
                    'start' => date('Y-m-d', strtotime('next Friday')),
                    'category' => 'holiday',
                    'status' => 'approved',
                    'description' => 'Company-wide holiday.',
                    'textColor' => '#ffffff',
                    'backgroundColor' => '#EF4444',
                    'borderColor' => '#DC2626',
                    'allDay' => true,
                ],
            ];
        }

        if ($employeeId && ($category === 'all' || $category === 'leave')) {
            $leaveQuery = LeaveApplication::query()
                ->where('employee_id', $employeeId);

            if ($start && $end) {
                $leaveQuery->whereBetween('date_from', [$start, $end]);
            }
            if ($status && $status !== 'all') {
                $leaveQuery->where('status', (string) $status);
            }

            foreach ($leaveQuery->get() as $leave) {
                $events[] = [
                    'id' => 'leave-'.$leave->id,
                    'title' => $leave->type ?? 'Leave',
                    'start' => optional($leave->date_from)->toDateString(),
                    'end' => $leave->date_to ? $leave->date_to->toDateString() : null,
                    'category' => 'leave',
                    'status' => $leave->status,
                    'description' => $leave->reason ?? '',
                    'textColor' => '#ffffff',
                    'backgroundColor' => $leave->status === 'approved' ? '#10B981' : ($leave->status === 'rejected' ? '#EF4444' : '#F59E0B'),
                    'borderColor' => $leave->status === 'approved' ? '#059669' : ($leave->status === 'rejected' ? '#DC2626' : '#D97706'),
                    'allDay' => true,
                ];
            }
        }

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
                    'start' => optional($training->date_from)->toDateString(),
                    'end' => $training->date_to ? $training->date_to->toDateString() : null,
                    'category' => 'training',
                    'status' => $training->status,
                    'description' => $training->provider ?? '',
                    'textColor' => '#ffffff',
                    'backgroundColor' => $training->status === 'approved' ? '#2563EB' : ($training->status === 'rejected' ? '#EF4444' : '#38BDF8'),
                    'borderColor' => $training->status === 'approved' ? '#1D4ED8' : ($training->status === 'rejected' ? '#DC2626' : '#0EA5E9'),
                    'allDay' => true,
                ];
            }
        }

        $events = array_merge($events, $holidays, $customEvents);

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

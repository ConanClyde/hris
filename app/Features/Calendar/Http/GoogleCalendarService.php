<?php

namespace App\Features\Calendar\Http;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait GoogleCalendarService
{
    /**
     * Fetch Philippine public holidays from Google Calendar API.
     *
     * Uses the public Philippine holiday calendar with 24-hour caching.
     * Returns an empty array on failure (graceful degradation).
     */
    private function getGoogleCalendarHolidays(string $startStr, string $endStr): array
    {
        $apiKey = config('services.google.calendar_api_key');

        if (! $apiKey) {
            return [];
        }

        $cacheKey = "google_holidays_{$startStr}_{$endStr}";

        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($apiKey, $startStr, $endStr) {
            $calendarId = 'en.philippines#holiday@group.v.calendar.google.com';
            $url = 'https://www.googleapis.com/calendar/v3/calendars/'.urlencode($calendarId).'/events';

            try {
                $timeMin = (new \DateTime($startStr))->format(\DateTime::RFC3339);
                $timeMax = (new \DateTime($endStr))->format(\DateTime::RFC3339);

                $response = Http::get($url, [
                    'key' => $apiKey,
                    'timeMin' => $timeMin,
                    'timeMax' => $timeMax,
                    'singleEvents' => 'true',
                    'orderBy' => 'startTime',
                ]);

                if ($response->failed()) {
                    Log::error('Google Calendar API Error: '.$response->body());

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
                            'extendedProps' => [
                                'type' => 'google_holiday',
                            ],
                        ];
                    }
                }

                return $holidays;
            } catch (\Exception $e) {
                Log::error('Google Calendar Exception: '.$e->getMessage());

                return [];
            }
        });
    }

    /**
     * Build custom holiday events for FullCalendar from database records.
     */
    private function buildCustomHolidayEvents($customHolidays, string $start, string $end): array
    {
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
                    'extendedProps' => [
                        'type' => 'custom_holiday',
                        'category_label' => $holiday->category,
                    ],
                ];
            }
        }

        return $customEvents;
    }

    /**
     * Build leave events for FullCalendar.
     */
    private function buildLeaveEvents($leaves): array
    {
        $events = [];

        foreach ($leaves as $leave) {
            $events[] = [
                'id' => 'leave-'.$leave->id,
                'title' => trim(($leave->employee_name ? $leave->employee_name.': ' : '').($leave->type ?? 'Leave')),
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

        return $events;
    }

    /**
     * Build training events for FullCalendar.
     */
    private function buildTrainingEvents($trainings): array
    {
        $events = [];

        foreach ($trainings as $training) {
            $events[] = [
                'id' => 'training-'.$training->id,
                'title' => trim(($training->employee_name ? $training->employee_name.': ' : '').($training->title ?? 'Training')),
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

        return $events;
    }
}

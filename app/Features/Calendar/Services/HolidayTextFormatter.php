<?php

namespace App\Features\Calendar\Services;

use App\Features\Calendar\Models\CustomHoliday;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HolidayTextFormatter
{
    public function formatUpcomingHolidays(int $limit = 5): string
    {
        $items = $this->getUpcomingHolidayItems();
        if ($items === []) {
            return 'No upcoming holidays.';
        }

        $lines = array_slice($items, 0, $limit);

        return collect($lines)->map(function (array $item) {
            $suffix = '';
            if (($item['source'] ?? '') === 'custom' && isset($item['category'])) {
                $suffix = " ({$item['category']})";
            } elseif (($item['source'] ?? '') === 'google') {
                $suffix = ' (Google)';
            }

            return "- {$item['date']}: {$item['title']}{$suffix}";
        })->implode("\n");
    }

    /**
     * @return array<int, array{title: string, date: string, timestamp: int, source: string, category?: string}>
     */
    private function getUpcomingHolidayItems(): array
    {
        $today = now()->startOfDay();
        $end = $today->copy()->addMonths(3);
        $items = [];

        $googleEvents = $this->fetchGoogleCalendarHolidays($today->toDateString(), $end->toDateString());
        foreach ($googleEvents as $event) {
            $start = $event['start'] ?? null;
            if (! is_string($start) || $start === '') {
                continue;
            }
            $date = substr($start, 0, 10);
            $timestamp = strtotime($date);
            if (! $timestamp) {
                continue;
            }
            $items[] = [
                'title' => $event['title'] ?? 'Holiday',
                'date' => $date,
                'timestamp' => $timestamp,
                'source' => 'google',
            ];
        }

        if (Schema::hasTable('custom_holidays')) {
            $customHolidays = CustomHoliday::query()->get();
            foreach ($customHolidays as $holiday) {
                $date = $holiday->date?->copy();
                if (! $date) {
                    continue;
                }

                if ($holiday->is_recurring) {
                    $date = $date->setYear((int) $today->format('Y'));
                    if ($date->lt($today)) {
                        $date = $date->addYear();
                    }
                }

                if ($date->lt($today) || $date->gt($end)) {
                    continue;
                }

                $items[] = [
                    'title' => $holiday->title,
                    'date' => $date->toDateString(),
                    'timestamp' => $date->timestamp,
                    'source' => 'custom',
                    'category' => $holiday->category ?? '',
                ];
            }
        }

        usort($items, fn ($a, $b) => $a['timestamp'] <=> $b['timestamp']);

        $deduped = [];
        foreach ($items as $item) {
            $key = $item['date'].'|'.$item['title'];
            if (isset($deduped[$key])) {
                continue;
            }
            $deduped[$key] = $item;
        }

        return array_values($deduped);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchGoogleCalendarHolidays(string $startStr, string $endStr): array
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

                        $holidays[] = [
                            'title' => $item['summary'] ?? 'Holiday',
                            'start' => $start,
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
}

<?php

declare(strict_types=1);

namespace App\Features\Training\Services;

use App\Features\Training\Models\Training;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TrainingCalendarService
{
    /**
     * Get training events for calendar view.
     *
     * @param  string  $viewType  'month'|'week'|'day'
     * @param  string  $date  Reference date (Y-m-d format)
     * @param  int|null  $employeeId  Filter by employee (null for all)
     * @return Collection<int, array{
     *     id: int,
     *     title: string,
     *     start: string,
     *     end: string,
     *     allDay: bool,
     *     type: string,
     *     status: string,
     *     provider: string|null,
     *     hours: float,
     *     participants: int,
     *     color: string,
     *     url: string|null
     * }>
     */
    public function getEvents(string $viewType, string $date, ?int $employeeId = null): Collection
    {
        $query = Training::query();

        // Date range based on view type
        $range = $this->getDateRange($viewType, $date);
        $query->where(function ($q) use ($range) {
            $q->whereBetween('date_from', [$range['start'], $range['end']])
                ->orWhereBetween('date_to', [$range['start'], $range['end']])
                ->orWhere(function ($sq) use ($range) {
                    $sq->where('date_from', '<=', $range['start'])
                        ->where('date_to', '>=', $range['end']);
                });
        });

        if ($employeeId !== null) {
            $query->where('employee_fk', $employeeId);
        }

        return $query->orderBy('date_from')
            ->get()
            ->map(fn (Training $training) => [
                'id' => $training->id,
                'title' => $training->title,
                'start' => $this->formatDateTime($training->date_from, $training->time_from),
                'end' => $this->formatDateTime($training->date_to ?? $training->date_from, $training->time_to),
                'allDay' => $training->time_from === null,
                'type' => $training->type,
                'status' => $training->status,
                'provider' => $training->provider,
                'hours' => $training->hours,
                'participants' => is_array($training->participants) ? count($training->participants) : 0,
                'color' => $this->getEventColor($training->type, $training->status),
                'url' => $training->employee_fk ? '/hr/trainings/'.$training->id : null,
            ]);
    }

    /**
     * Get training summary statistics.
     *
     * @param  string  $dateFrom  Y-m-d format
     * @param  string  $dateTo  Y-m-d format
     * @return array{
     *     total_trainings: int,
     *     total_hours: float,
     *     by_type: array<string, int>,
     *     by_status: array<string, int>,
     *     upcoming: int,
     *     completed: int
     * }
     */
    public function getSummary(string $dateFrom, string $dateTo): array
    {
        $trainings = Training::query()
            ->whereBetween('date_from', [$dateFrom, $dateTo])
            ->orWhereBetween('date_to', [$dateFrom, $dateTo])
            ->get();

        $totalHours = $trainings->sum('hours');
        $byType = $trainings->groupBy('type')->map->count()->toArray();
        $byStatus = $trainings->groupBy('status')->map->count()->toArray();

        $today = now()->format('Y-m-d');
        $upcoming = $trainings->where('date_from', '>', $today)->count();
        $completed = $trainings->where('date_to', '<', $today)->count();

        return [
            'total_trainings' => $trainings->count(),
            'total_hours' => (float) $totalHours,
            'by_type' => $byType,
            'by_status' => $byStatus,
            'upcoming' => $upcoming,
            'completed' => $completed,
        ];
    }

    /**
     * Get date range for calendar view.
     *
     * @return array{start: string, end: string}
     */
    private function getDateRange(string $viewType, string $date): array
    {
        $carbon = Carbon::parse($date);

        return match ($viewType) {
            'month' => [
                'start' => $carbon->copy()->startOfMonth()->subWeek()->format('Y-m-d'),
                'end' => $carbon->copy()->endOfMonth()->addWeek()->format('Y-m-d'),
            ],
            'week' => [
                'start' => $carbon->copy()->startOfWeek()->format('Y-m-d'),
                'end' => $carbon->copy()->endOfWeek()->format('Y-m-d'),
            ],
            'day' => [
                'start' => $carbon->format('Y-m-d'),
                'end' => $carbon->format('Y-m-d'),
            ],
            default => [
                'start' => $carbon->copy()->startOfMonth()->format('Y-m-d'),
                'end' => $carbon->copy()->endOfMonth()->format('Y-m-d'),
            ],
        };
    }

    /**
     * Format date and time for calendar.
     */
    private function formatDateTime(?Carbon $date, ?Carbon $time): string
    {
        if ($date === null) {
            return now()->format('Y-m-d\TH:i:s');
        }

        if ($time === null) {
            return $date->format('Y-m-d');
        }

        return $date->format('Y-m-d').'T'.$time->format('H:i:s');
    }

    /**
     * Get event color based on type and status.
     */
    private function getEventColor(string $type, string $status): string
    {
        // Status-based colors
        if ($status === 'completed') {
            return '#10B981'; // Green
        }
        if ($status === 'cancelled') {
            return '#EF4444'; // Red
        }

        // Type-based colors for upcoming/planned
        return match ($type) {
            'Technical' => '#3B82F6',      // Blue
            'Managerial' => '#8B5CF6',   // Purple
            'Supervisory' => '#F59E0B',  // Amber
            'Clerical' => '#6B7280',     // Gray
            'Research' => '#EC4899',     // Pink
            'Orientation' => '#14B8A6',  // Teal
            default => '#6366F1',        // Indigo
        };
    }
}

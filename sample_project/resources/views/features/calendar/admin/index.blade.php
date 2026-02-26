@php
    echo view('admin.calendar.index', [
        'customHolidays' => $customHolidays ?? collect(),
    ])->render();
@endphp


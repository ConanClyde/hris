<?php

namespace App\Features\Calendar\Http\Controllers\Admin;

use App\Features\Calendar\Events\CustomHolidayCreated;
use App\Features\Calendar\Events\CustomHolidayDeleted;
use App\Features\Calendar\Events\CustomHolidayUpdated;
use App\Features\Calendar\Models\CustomHoliday;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomHolidayController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.calendar');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'required|string|in:regular,special,local',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $holiday = CustomHoliday::create($validated);

        event(new CustomHolidayCreated($holiday));

        return redirect()->route('admin.custom-holidays.index')
            ->with('success', 'Custom holiday created successfully.');
    }

    public function update(Request $request, $id)
    {
        $holiday = CustomHoliday::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'required|string|in:regular,special,local',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        $holiday->update($validated);

        event(new CustomHolidayUpdated($holiday));

        return redirect()->route('admin.custom-holidays.index')
            ->with('success', 'Custom holiday updated successfully.');
    }

    public function destroy($id)
    {
        $holiday = CustomHoliday::findOrFail($id);
        $holiday->delete();

        event(new CustomHolidayDeleted((int) $id));

        return redirect()->route('admin.custom-holidays.index')
            ->with('success', 'Custom holiday deleted successfully.');
    }
}

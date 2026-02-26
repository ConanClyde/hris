<?php

namespace App\Features\Calendar\Http\Controllers\Admin;

use App\Features\Calendar\Events\CustomHolidayCreated;
use App\Features\Calendar\Events\CustomHolidayDeleted;
use App\Features\Calendar\Events\CustomHolidayUpdated;
use App\Features\Calendar\Models\CustomHoliday;
use App\Http\Controllers\Controller;
use App\Mail\HolidayAddedMail;
use App\Mail\HolidayUpdatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomHolidayController extends Controller
{
    public function index(): Response
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

        // Broadcast email to all active users about the new holiday
        $recipients = User::query()
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($recipients !== []) {
            $addedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'System';
            Mail::to($recipients)->queue(new HolidayAddedMail($holiday, $addedBy));
        }

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

        // Broadcast email to all active users about the updated holiday
        $recipients = User::query()
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($recipients !== []) {
            $updatedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'System';
            Mail::to($recipients)->queue(new HolidayUpdatedMail($holiday, $updatedBy));
        }

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

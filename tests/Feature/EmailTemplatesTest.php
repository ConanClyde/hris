<?php

use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Notices\Models\Notice;
use App\Features\Training\Models\Training;
use App\Mail\HolidayAddedMail;
use App\Mail\LeaveSubmittedMail;
use App\Mail\NoticeCreatedMail;
use App\Mail\TrainingAssignedMail;
use App\Mail\UserApprovedMail;
use App\Mail\UserRegisteredMail;
use App\Mail\UserRejectedMail;
use App\Models\User;
use Carbon\Carbon;

it('renders leave submitted email', function (): void {
    $leave = new LeaveApplication([
        'employee_name' => 'John Doe',
        'type' => 'Vacation',
        'date_from' => Carbon::parse('2025-01-01'),
        'total_days' => 2,
        'reason' => 'Family trip',
    ]);
    $leave->created_at = Carbon::parse('2025-01-01 10:00:00');

    $mailable = new LeaveSubmittedMail($leave);

    $html = $mailable->render();

    expect($html)
        ->toContain('Leave Application Submitted')
        ->toContain('John Doe')
        ->toContain('Vacation');
});

it('renders holiday added email', function (): void {
    $holiday = new CustomHoliday([
        'title' => 'Founding Day',
        'date' => Carbon::parse('2025-02-01'),
        'category' => 'regular',
        'description' => 'Company founding celebration',
    ]);

    $mailable = new HolidayAddedMail($holiday, 'Admin User');

    $html = $mailable->render();

    expect($html)
        ->toContain('New Holiday Added')
        ->toContain('Founding Day')
        ->toContain('Admin User');
});

it('renders notice created email', function (): void {
    $notice = new Notice([
        'title' => 'System Maintenance',
        'message' => 'The system will be offline this weekend.',
        'type' => 'warning',
    ]);
    $notice->created_at = Carbon::parse('2025-03-01 09:30:00');

    $mailable = new NoticeCreatedMail($notice, 'HR Manager');

    $html = $mailable->render();

    expect($html)
        ->toContain('New Notice Published')
        ->toContain('System Maintenance')
        ->toContain('HR Manager');
});

it('renders training assigned email', function (): void {
    $training = new Training([
        'title' => 'Workplace Safety',
        'description' => 'Mandatory annual safety training.',
        'date_from' => Carbon::parse('2025-04-01'),
        'date_to' => Carbon::parse('2025-04-15'),
    ]);

    $mailable = new TrainingAssignedMail($training, 'Jane Employee', 'HR Coordinator');

    $html = $mailable->render();

    expect($html)
        ->toContain('Training Assigned')
        ->toContain('Workplace Safety')
        ->toContain('Jane Employee')
        ->toContain('HR Coordinator');
});

it('renders user registered email', function (): void {
    $user = new User([
        'name' => 'John Admin',
        'first_name' => 'John',
        'last_name' => 'Admin',
        'email' => 'john@example.com',
        'role' => 'admin',
    ]);
    $user->created_at = Carbon::parse('2025-05-01 08:00:00');

    $mailable = new UserRegisteredMail($user, '127.0.0.1');

    $html = $mailable->render();

    expect($html)
        ->toContain('New User Registration')
        ->toContain('john@example.com')
        ->toContain('admin');
});

it('renders user approved email', function (): void {
    $user = new User([
        'name' => 'Jane Employee',
        'first_name' => 'Jane',
        'last_name' => 'Employee',
        'email' => 'jane@example.com',
        'role' => 'employee',
    ]);

    $mailable = new UserApprovedMail($user, 'HR Manager');

    $html = $mailable->render();

    expect($html)
        ->toContain('Account Approved')
        ->toContain('Jane Employee')
        ->toContain('HR Manager');
});

it('renders user rejected email', function (): void {
    $user = new User([
        'name' => 'Jane Employee',
        'first_name' => 'Jane',
        'last_name' => 'Employee',
        'email' => 'jane@example.com',
        'role' => 'employee',
    ]);

    $mailable = new UserRejectedMail($user, 'HR Manager');

    $html = $mailable->render();

    expect($html)
        ->toContain('Account Rejected')
        ->toContain('Jane Employee')
        ->toContain('HR Manager');
});

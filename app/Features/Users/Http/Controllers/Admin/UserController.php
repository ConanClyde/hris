<?php

namespace App\Features\Users\Http\Controllers\Admin;

use App\Events\UserApproved;
use App\Events\UserRegistered;
use App\Events\UserRejected;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\UserApprovedMail;
use App\Mail\UserRegisteredMail;
use App\Mail\UserRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::query()
            ->select([
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'users.role',
                'users.is_active',
                'users.status',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.name_extension',
                'users.avatar',
                'users.created_at',
                'users.updated_at',
                // Employee / organizational fields for employee users
                'employees.position',
                'employees.classification',
                'employees.date_hired',
                'employees.division',
                'employees.subdivision',
                'employees.section',
                'employees.division_id',
                'employees.subdivision_id',
                'employees.section_id',
            ])
            ->leftJoin('employees', 'employees.user_id', '=', 'users.id');
        $status = $request->route('status') ?? $request->input('status');
        $isHr = Auth::user()?->isHr();

        if ($isHr) {
            $query->where('users.role', UserRole::Employee->value);
        }

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(users.name) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(users.email) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('role')) {
            $query->where('users.role', (string) $request->input('role'));
        }

        if ($status && $status !== 'all') {
            if ($status === 'pending') {
                $query->where('users.status', 'pending');
            } elseif ($status === 'rejected') {
                $query->where('users.status', 'rejected');
            } elseif ($status === 'active') {
                $query->where('users.is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('users.is_active', false)->where('users.status', 'approved');
            } elseif ($status === 'approved') {
                $query->where('users.status', 'approved');
            }
        } else {
            // For "All" status - show both active and inactive (approved) users, exclude pending/rejected
            $query->where('users.status', 'approved');
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $users = $query->orderByDesc('users.created_at')->paginate(10)->appends($appendQuery);

        $page = str_starts_with((string) $request->route()?->getName(), 'hr.')
            ? 'HR/Users/Index'
            : 'Admin/Users/Index';

        $filters = array_merge($request->only(['search', 'role']), array_filter(['status' => $status]));

        $pendingCountQuery = User::where('status', 'pending');

        if ($isHr) {
            $pendingCountQuery->where('role', UserRole::Employee->value);
        }

        $payload = [
            'users' => $users,
            'filters' => $filters,
            'pendingCount' => $pendingCountQuery->count(),
        ];

        return Inertia::render($page, $payload);
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:'.implode(',', array_column(UserRole::cases(), 'value')),
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_extension' => 'nullable|string|max:50',
            // Employee-specific fields (conditional validation)
            'sex' => 'required_if:role,employee|in:male,female',
            'date_of_birth' => 'required_if:role,employee|date',
            'date_hired' => 'required_if:role,employee|date',
            'division_id' => 'required_if:role,employee|exists:divisions,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'section_id' => 'nullable|exists:sections,id',
            'position' => 'required_if:role,employee|string|max:255',
            'classification' => 'required_if:role,employee|in:Regular,Detailed,COS',
        ]);

        if ($currentUser?->isHr() && ($validated['role'] ?? null) !== UserRole::Employee->value) {
            return redirect()->back()->with('error', 'HR users can only create Employee accounts.');
        }

        $user = DB::transaction(function () use ($validated) {
            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'username' => strtolower($validated['username']),
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'name_extension' => $validated['name_extension'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => true,
                'status' => 'approved', // Auto-approved when created by admin/HR
            ]);

            // If employee, create employee record
            if ($validated['role'] === UserRole::Employee->value) {
                // Get division/subdivision/section names
                $divisionName = null;
                $subdivisionName = null;
                $sectionName = null;

                if (!empty($validated['division_id'])) {
                    $division = \App\Features\Employees\Models\Division::find($validated['division_id']);
                    $divisionName = $division?->name;
                }
                if (!empty($validated['subdivision_id'])) {
                    $subdivision = \App\Features\Employees\Models\Subdivision::find($validated['subdivision_id']);
                    $subdivisionName = $subdivision?->name;
                }
                if (!empty($validated['section_id'])) {
                    $section = \App\Features\Employees\Models\Section::find($validated['section_id']);
                    $sectionName = $section?->name;
                }

                \App\Features\Employees\Models\Employee::create([
                    'user_id' => $user->id,
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'name_extension' => $validated['name_extension'] ?? null,
                    'email' => $validated['email'],
                    'position' => $validated['position'] ?? null,
                    'classification' => $validated['classification'] ?? null,
                    'date_hired' => $validated['date_hired'] ?? null,
                    'division_id' => $validated['division_id'] ?? null,
                    'subdivision_id' => $validated['subdivision_id'] ?? null,
                    'section_id' => $validated['section_id'] ?? null,
                    'division' => $divisionName,
                    'subdivision' => $subdivisionName,
                    'section' => $sectionName,
                    'status' => 'active',
                ]);

                // Create PDS record for sex and date_of_birth
                if (!empty($validated['sex']) || !empty($validated['date_of_birth'])) {
                    $employeeRecord = \App\Features\Employees\Models\Employee::where('user_id', $user->id)->first();
                    if ($employeeRecord) {
                        $pds = \App\Features\Pds\Models\Pds::create([
                            'employee_id' => $employeeRecord->id,
                            'status' => 'draft',
                        ]);
                        \App\Features\Pds\Models\PdsPersonal::create([
                            'pds_id' => $pds->id,
                            'sex' => $validated['sex'] ?? null,
                            'dob' => $validated['date_of_birth'] ?? null,
                            'surname' => $validated['last_name'] ?? null,
                            'first_name' => $validated['first_name'] ?? null,
                            'middle_name' => $validated['middle_name'] ?? null,
                            'name_extension' => $validated['name_extension'] ?? null,
                            'email' => $validated['email'] ?? null,
                        ]);
                    }
                }
            }

            return $user;
        });

        // Broadcast user registered event
        broadcast(new UserRegistered($user->fresh(['employee'])))->toOthers();

        // Email HR / Admin users about the new user created by admin / HR
        $hrRecipients = User::query()
            ->whereIn('role', [UserRole::Admin->value, UserRole::Hr->value])
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($hrRecipients !== []) {
            Mail::to($hrRecipients)->queue(new UserRegisteredMail($user, $request->ip()));
        }

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Prevent HR from managing Admin users
        if ($currentUser?->isHr() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage admin accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'is_active' => 'boolean',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'is_active' => $validated['is_active'] ?? $user->is_active,
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Prevent HR from managing Admin users
        if ($currentUser?->isHr() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot delete admin accounts.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($currentUser?->isHr() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot approve admin accounts.');
        }

        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending users can be approved.');
        }

        $user = DB::transaction(function () use ($user) {
            $user->update([
                'status' => 'approved',
                'is_active' => true,
            ]);

            return $user->refresh();
        });

        $approverName = $currentUser?->full_name ?? 'Admin';
        broadcast(new UserApproved($user, $approverName))->toOthers();

        if ($user->email) {
            Mail::to($user->email)->queue(new UserApprovedMail($user, $approverName));
        }

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($currentUser?->isHr() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot reject admin accounts.');
        }

        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending users can be rejected.');
        }

        $user = DB::transaction(function () use ($user) {
            $user->update([
                'status' => 'rejected',
                'is_active' => false,
            ]);

            return $user->refresh();
        });

        $approverName = $currentUser?->full_name ?? 'Admin';
        broadcast(new UserRejected($user, $approverName))->toOthers();

        if ($user->email) {
            Mail::to($user->email)->queue(new UserRejectedMail($user, $approverName));
        }

        return redirect()->back()->with('success', 'User rejected successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Prevent HR from managing Admin users
        if ($currentUser?->isHr() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot toggle admin account status.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        // If user is being activated, broadcast approval event
        if ($user->is_active) {
            $approverName = Auth::user()?->full_name ?? 'Admin';
            broadcast(new UserApproved($user, $approverName))->toOthers();

            if ($user->email) {
                Mail::to($user->email)->queue(new UserApprovedMail($user, $approverName));
            }
        }

        return redirect()->back()->with('success', "User {$status} successfully.");
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $action = $validated['action'];
        $userIds = $validated['user_ids'];
        $currentUser = Auth::user();

        // Prevent HR from managing Admin users in bulk
        if ($currentUser?->isHr()) {
            $hasAdmin = User::whereIn('id', $userIds)->where('role', UserRole::Admin->value)->exists();
            if ($hasAdmin) {
                return redirect()->back()->with('error', 'HR users cannot perform bulk actions on admin accounts.');
            }
        }

        $count = count($userIds);

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = "Successfully activated {$count} users.";
                break;
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = "Successfully deactivated {$count} users.";
                break;
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = "Successfully deleted {$count} users.";
                break;
            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }

        return redirect()->back()->with('success', $message);
    }
}

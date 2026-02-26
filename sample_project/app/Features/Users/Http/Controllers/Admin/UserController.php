<?php

namespace App\Features\Users\Http\Controllers\Admin;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\Auth\Events\ProfileUpdated;
use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['employee' => function ($q) {
            $q->select('id', 'user_id', 'first_name', 'middle_name', 'last_name',
                'name_extension', 'email', 'position', 'classification', 'date_hired',
                'division', 'subdivision', 'section', 'division_id', 'subdivision_id',
                'section_id', 'status');
            $q->with('pds.personal');
        }]);

        // Search by name or email
        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) like ?', ["%{$search}%"]);
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', (string) $request->input('role'));
        }

        // Filter by status (from users table is_active or employee status)
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = (string) $request->input('status');
            if (in_array($status, ['active', 'inactive'])) {
                $query->where('is_active', $status === 'active');
            }
        } elseif ($request->filled('user_status')) {
            $status = (string) $request->input('user_status');
            if (in_array($status, ['active', 'inactive'])) {
                $query->where('is_active', $status === 'active');
            }
        }

        $paginatedUsers = $query->orderByDesc('created_at')->paginate(10)->appends($request->query());

        // Transform users to include employee data in the format the view expects.
        // Uses optional() for graceful degradation when user has no employee record.
        $transformedUsers = $paginatedUsers->through(function ($user) {
            $employee = optional($user->employee);

            return (object) [
                'id' => $user->id,
                'user_id' => $user->user_id,
                'name' => $user->name,
                'display_name' => $user->display_name,
                'first_name' => $employee?->first_name ?? '',
                'middle_name' => $employee?->middle_name ?? '',
                'last_name' => $employee?->last_name ?? '',
                'name_extension' => $employee?->name_extension ?? '',
                'email' => $user->email,
                'contact_number' => null, // Not stored in current schema
                'date_hired' => $employee?->date_hired?->format('Y-m-d') ?? null,
                'position' => $employee?->position ?? '',
                'division' => $employee?->division ?? '',
                'subdivision' => $employee?->subdivision ?? '',
                'section' => $employee?->section ?? '',
                'division_id' => $employee?->division_id,
                'subdivision_id' => $employee?->subdivision_id,
                'section_id' => $employee?->section_id,
                'classifications' => $employee?->classification ?? '',
                'sex' => $employee?->pds?->personal?->sex ?? '',
                'date_of_birth' => $employee?->pds?->personal?->dob?->format('Y-m-d') ?? null,
                'role' => ucfirst($user->role ?? 'employee'),
                'status' => $user->is_active ? 'active' : 'inactive',
                'employee_id' => $employee?->id ?? '',
                'created_at' => $user->created_at,
                'avatar' => null,
            ];
        });

        $pendingCount = 0; // No pending status in current schema, could be added if needed

        return view('features.users.admin.index', [
            'users' => $transformedUsers,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function store(Request $request, ActivityLogger $activityLogger)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|unique:users,user_id|max:255',
            // 'name' => 'required|string|max:255', // Auto-generated
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,hr,employee',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_extension' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'classification' => 'required|string|max:255', // Added
            'division' => 'required|exists:divisions,id', // ID
            'subdivision' => 'nullable|exists:subdivisions,id', // ID
            'unit_section' => 'required|exists:sections,id', // ID
            'date_hired' => 'required|date|before_or_equal:today',
            'date_of_birth' => 'required|date|before:-18 years',
            'sex' => 'required|in:male,female',
        ]);

        // Resolve organizational units
        $division = Division::find($validated['division']);
        $subdivisionId = $validated['subdivision'] ?? null;
        $subdivision = $subdivisionId ? Subdivision::find($subdivisionId) : null;
        $section = Section::find($validated['unit_section']);

        DB::transaction(function () use ($validated, $division, $subdivision, $section, $activityLogger) {
            $middleName = $validated['middle_name'] ?? null;
            $nameExtension = $validated['name_extension'] ?? null;

            // 1. Create User
            $user = User::create([
                'user_id' => $validated['user_id'],
                'name' => trim($validated['first_name'].' '.($middleName ? $middleName.' ' : '').$validated['last_name']),
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'last_name' => $validated['last_name'],
                'name_extension' => $nameExtension,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'], // Assuming role is already lowercase/valid from input? Validation says admin,hr,employee.
                'is_active' => true,
            ]);

            // 2. Create Employee Record
            $employee = Employee::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'last_name' => $validated['last_name'],
                'name_extension' => $nameExtension,
                'email' => $validated['email'],
                'position' => $validated['position'],
                'classification' => $validated['classification'],
                'date_hired' => $validated['date_hired'],
                'division' => $division->name,
                'subdivision' => $subdivision?->name,
                'section' => $section->name,
                'division_id' => $division->id,
                'subdivision_id' => $subdivision?->id,
                'section_id' => $section->id,
                'status' => 'active',
            ]);

            // 3. Create PDS Record (Draft)
            $pds = Pds::create([
                'employee_id' => $employee->id,
                'status' => 'draft',
            ]);

            // 4. Create PDS Personal Record
            PdsPersonal::create([
                'pds_id' => $pds->id,
                'surname' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'name_extension' => $nameExtension,
                'sex' => $validated['sex'],
                'dob' => $validated['date_of_birth'],
                'email' => $validated['email'],
            ]);

            $activityLogger->logCreate('User', $user->id, [
                'action' => 'admin_create_user',
                'email' => $user->email,
                'role' => $user->role,
            ]);
        });

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id, ActivityLogger $activityLogger)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|string|max:255|unique:users,user_id,'.$id,
            // 'name' => 'required|string|max:255', // Auto-generated
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,hr,employee',
            'is_active' => 'boolean',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_extension' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'classification' => 'required|string|max:255',
            'division' => 'required|exists:divisions,id',
            'subdivision' => 'nullable|exists:subdivisions,id',
            'unit_section' => 'required|exists:sections,id',
            'date_hired' => 'required|date|before_or_equal:today',
            'date_of_birth' => 'required|date|before:-18 years',
            'sex' => 'required|in:male,female',
        ]);

        // Resolve organizational units
        $division = Division::find($validated['division']);
        $subdivisionId = $validated['subdivision'] ?? null;
        $subdivision = $subdivisionId ? Subdivision::find($subdivisionId) : null;
        $section = Section::find($validated['unit_section']);

        DB::transaction(function () use ($validated, $user, $division, $subdivision, $section, $activityLogger) {
            $middleName = $validated['middle_name'] ?? null;
            $nameExtension = $validated['name_extension'] ?? null;

            $user->update([
                'user_id' => $validated['user_id'],
                'name' => trim($validated['first_name'].' '.($middleName ? $middleName.' ' : '').$validated['last_name']),
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'last_name' => $validated['last_name'],
                'name_extension' => $nameExtension,
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? $user->is_active,
            ]);

            // Update or create employee record
            $employee = Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $validated['first_name'],
                    'middle_name' => $middleName,
                    'last_name' => $validated['last_name'],
                    'name_extension' => $nameExtension,
                    'email' => $validated['email'],
                    'position' => $validated['position'],
                    'classification' => $validated['classification'],
                    'date_hired' => $validated['date_hired'],
                    'division' => $division->name,
                    'subdivision' => $subdivision?->name,
                    'section' => $section->name,
                    'division_id' => $division->id,
                    'subdivision_id' => $subdivision?->id,
                    'section_id' => $section->id,
                    // Status?
                ]
            );

            // Ensure PDS exists
            $pds = Pds::firstOrCreate(
                ['employee_id' => $employee->id],
                ['status' => 'draft']
            );

            // Update PDS Personal
            PdsPersonal::updateOrCreate(
                ['pds_id' => $pds->id],
                [
                    'surname' => $validated['last_name'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $middleName,
                    'name_extension' => $nameExtension,
                    'sex' => $validated['sex'],
                    'dob' => $validated['date_of_birth'],
                    'email' => $validated['email'],
                ]
            );

            $activityLogger->logUpdate('User', $user->id, [], ['action' => 'admin_update_user']);
        });

        $this->broadcastProfileIfCurrentUser((int) $user->id);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    private function broadcastProfileIfCurrentUser(int $userId): void
    {
        if (Auth::id() !== $userId) {
            return;
        }

        session([
            'email' => request()->input('email', Auth::user()->email ?? ''),
            'role' => request()->input('role', Auth::user()->role ?? ''),
        ]);

        $user = User::with('employee')->find($userId);
        $displayName = $user->name ?? 'User';
        $emp = $user->employee;
        if ($emp && trim($emp->first_name.' '.$emp->last_name)) {
            $displayName = trim($emp->first_name.' '.$emp->last_name);
        }
        $initial = strtoupper(mb_substr($displayName, 0, 1)) ?: 'U';
        $avatarPath = session('avatar');
        $profile = [
            'display_name' => $displayName,
            'email' => $user->email,
            'initial' => $initial,
            'avatar_url' => $avatarPath ? asset('storage/'.$avatarPath) : null,
        ];

        event(new ProfileUpdated($userId, $profile));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete associated employee record first (if cascade not set)
        Employee::where('user_id', $user->id)->delete();

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.users')->with('success', "User {$status} successfully.");
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
                // Delete associated employees first
                Employee::whereIn('user_id', $userIds)->delete();
                User::whereIn('id', $userIds)->delete();
                $message = "Successfully deleted {$count} users.";
                break;
            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }

        return redirect()->route('admin.users')->with('success', $message);
    }
}

<?php

namespace App\Features\Users\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) like ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('role')) {
            $query->where('role', (string) $request->input('role'));
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = (string) $request->input('status');
            if ($status === 'pending' || $status === 'rejected') {
                $query->where('is_active', false);
            } elseif (in_array($status, ['active', 'inactive'])) {
                $query->where('is_active', $status === 'active');
            }
        }

        $users = $query->orderByDesc('created_at')->paginate(10)->appends($request->query());

        $page = str_starts_with((string) $request->route()?->getName(), 'hr.')
            ? 'HR/Users/Index'
            : 'Admin/Users/Index';

        $payload = [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
        ];
        if (! str_starts_with((string) $request->route()?->getName(), 'hr.')) {
            $payload['pendingCount'] = User::where('is_active', false)->count();
        }

        return Inertia::render($page, $payload);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,hr,employee',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            User::create([
                'name' => $validated['name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => true,
            ]);
        });

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,hr,employee',
            'is_active' => 'boolean',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? $user->is_active,
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminAudit\AdminAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status', 'active');

        return Inertia::render('Admin/Users/Index', [
            'filters' => [
                'search' => $search,
                'role' => $role,
                'status' => $status,
            ],
            'users' => User::query()
                ->when($status === 'deleted', fn ($query) => $query->onlyTrashed())
                ->when($search, fn ($query) => $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                }))
                ->when($role === 'admin', fn ($query) => $query->where('is_admin', true))
                ->when($role === 'user', fn ($query) => $query->where('is_admin', false))
                ->latest()
                ->paginate(15)
                ->withQueryString()
                ->through(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'isAdmin' => $user->is_admin,
                    'isMe' => (bool) request()->user()?->is($user),
                    'deletedAt' => $user->deleted_at?->toDateTimeString(),
                    'createdAt' => $user->created_at?->toDateTimeString(),
                    'updatedAt' => $user->updated_at?->toDateTimeString(),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Form', [
            'mode' => 'create',
            'user' => null,
        ]);
    }

    public function store(Request $request, AdminAuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => (bool) ($validated['is_admin'] ?? false),
        ]);

        $auditLogger->log($request->user(), 'user.created', $user, "Membuat user {$user->email}.", [
            'is_admin' => $user->is_admin,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User baru berhasil dibuat.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Form', [
            'mode' => 'edit',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'isAdmin' => $user->is_admin,
            ],
        ]);
    }

    public function update(Request $request, User $user, AdminAuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $newAdminStatus = (bool) ($validated['is_admin'] ?? false);

        if ($user->is_admin && ! $newAdminStatus && User::query()->where('is_admin', true)->count() <= 1) {
            return back()->withErrors(['role' => 'Admin terakhir tidak boleh diturunkan.']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_admin = $newAdminStatus;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $auditLogger->log($request->user(), 'user.updated', $user, "Memperbarui user {$user->email}.", [
            'is_admin' => $user->is_admin,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User berhasil diperbarui.');
    }

    public function toggleAdmin(Request $request, User $user, AdminAuditLogger $auditLogger): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return back()->withErrors(['role' => 'Admin tidak boleh mengubah role dirinya sendiri.']);
        }

        if ($user->is_admin && User::query()->where('is_admin', true)->count() <= 1) {
            return back()->withErrors(['role' => 'Admin terakhir tidak boleh diturunkan.']);
        }

        $user->update([
            'is_admin' => ! $user->is_admin,
        ]);

        $auditLogger->log($request->user(), 'user.role_toggled', $user, "Mengubah role user {$user->email}.", [
            'is_admin' => $user->is_admin,
        ]);

        return back()->with('status', $user->is_admin ? 'User diubah menjadi admin.' : 'Admin diubah menjadi user.');
    }

    public function destroy(Request $request, User $user, AdminAuditLogger $auditLogger): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return back()->withErrors(['delete' => 'Admin tidak boleh menghapus dirinya sendiri.']);
        }

        $email = $user->email;
        $user->delete();

        $auditLogger->log($request->user(), 'user.deleted', $user, "Menghapus user {$email}.");

        return redirect()->route('admin.users.index')->with('status', 'User berhasil dihapus.');
    }

    public function restore(Request $request, int $user, AdminAuditLogger $auditLogger): RedirectResponse
    {
        $trashedUser = User::withTrashed()->whereKey($user)->firstOrFail();
        $trashedUser->restore();

        $auditLogger->log($request->user(), 'user.restored', $trashedUser, "Memulihkan user {$trashedUser->email}.");

        return redirect()->route('admin.users.index')->with('status', 'User berhasil dipulihkan.');
    }
}

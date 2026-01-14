<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->query('role'); // admin|customer|null
        $roles = Role::orderBy('name')->get();

        $q = $request->query('q');

        $users = User::query()
            ->with('roles')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($role, function ($q2) use ($role) {
                $q2->whereHas('roles', fn($r) => $r->where('slug', $role));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'q', 'roles'));
    }

    public function edit(User $user): View
    {
        $user->load(['roles', 'addresses', 'orders']);

        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /*
    public function show(User $user): View
    {
        $user->load(['roles', 'addresses', 'orders']);

        $roles = Role::orderBy('name')->get();

        return view('admin.users.show', compact('user', 'roles'));
    }
    */
    public function update(Request $request, User $user): RedirectResponse
    {
        $roleSlugs = Role::pluck('slug')->all();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['string', 'in:' . implode(',', $roleSlugs)],
        ]);

        // Actualiza datos básicos
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Password opcional
        if (!empty($data['password'])) {
            $user->password = $data['password']; // se hashea por cast 'hashed'
        }

        $user->save();

        // Sync roles
        $roleIds = Role::whereIn('slug', $data['roles'] ?? [])->pluck('id')->all();
        $user->roles()->sync($roleIds);

        return back()->with('success', 'Usuario actualizado.');
    }
}

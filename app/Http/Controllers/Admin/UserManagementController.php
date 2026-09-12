<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderBy('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            User::create([
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => $request->string('password'),
                'is_admin' => true,
                'is_superadmin' => $request->input('role') === 'superadmin',
            ]);

            return back()->with('success', 'Akun admin baru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan akun admin. Silakan coba lagi.');
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = [
                'name' => $request->string('name'),
                'email' => $request->string('email'),
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->string('password');
            }

            $role = $request->input('role');

            if ($role !== null && ! $user->is(auth()->user())) {
                $data['is_superadmin'] = $role === 'superadmin';
            }

            $user->update($data);

            return back()->with('success', "Data akun {$user->name} berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data akun. Silakan coba lagi.');
        }
    }
}

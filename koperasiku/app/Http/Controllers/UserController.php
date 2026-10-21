<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const ROLES = ['admin', 'pengurus', 'bendahara', 'anggota'];

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $k = $request->search;
                $query->where(fn ($q) => $q->where('name', 'like', "%{$k}%")->orWhere('email', 'like', "%{$k}%"));
            })
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(self::ROLES)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
        $user->role = $data['role'];
        $user->is_active = (bool) $data['is_active'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(self::ROLES)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ]);

        // Lindungi admin aktif terakhir
        $demoting = $user->role === 'admin' && $user->is_active
            && ($data['role'] !== 'admin' || ! (bool) $data['is_active']);

        if ($demoting && ! $this->hasOtherActiveAdmin($user)) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['role' => 'Tidak dapat menonaktifkan atau menurunkan role admin terakhir.']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->is_active = (bool) $data['is_active'];
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        if ($request->user()->is($user) && ! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->role === 'admin' && $user->is_active && ! $this->hasOtherActiveAdmin($user)) {
            return back()->with('error', 'Admin terakhir tidak boleh dihapus.');
        }

        if ($user->simpananDibuat()->exists() || $user->simpananDiverifikasi()->exists()) {
            return back()->with('error', 'User memiliki riwayat transaksi. Nonaktifkan akun, jangan hapus.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    private function hasOtherActiveAdmin(User $user): bool
    {
        return User::where('role', 'admin')->where('is_active', true)->whereKeyNot($user->id)->exists();
    }
}

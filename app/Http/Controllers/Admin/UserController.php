<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,penjual,user',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'aktif';
        $validated['status_approval'] = $request->role === 'penjual' ? 'pending' : 'approved';

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,penjual,user',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}

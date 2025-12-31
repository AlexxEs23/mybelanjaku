<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfilController extends Controller
{
    public function show()
    {
        return view('profile', [
            'user' => Auth::user()
        ]);
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        User::where('id', Auth::id())->update($updateData);
        
        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
    
    public function applySeller()
    {
        $user = User::find(Auth::id());
        
        // Cek apakah user sudah penjual
        if ($user->role === 'penjual') {
            return redirect()->route('profile.show')->with('error', 'Anda sudah terdaftar sebagai penjual.');
        }
        
        // Update role menjadi penjual dengan status pending
        User::where('id', Auth::id())->update([
            'role' => 'penjual',
            'status_approval' => 'pending'
        ]);
        
        return redirect()->route('profile.show')->with('success', 'Pengajuan sebagai penjual berhasil! Silakan tunggu persetujuan dari admin. Anda akan menerima notifikasi setelah akun Anda disetujui.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerApprovalController extends Controller
{
    public function index()
    {
        $pendingSellers = User::where('role', 'penjual')
            ->where('status_approval', 'pending')
            ->latest()
            ->get();
            
        $approvedSellers = User::where('role', 'penjual')
            ->where('status_approval', 'approved')
            ->latest()
            ->get();
            
        $rejectedSellers = User::where('role', 'penjual')
            ->where('status_approval', 'rejected')
            ->latest()
            ->get();
        
        return view('admin.seller-approval', compact('pendingSellers', 'approvedSellers', 'rejectedSellers'));
    }
    
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'penjual') {
            return back()->with('error', 'User bukan penjual!');
        }
        
        $user->update([
            'status_approval' => 'approved'
        ]);
        
        return back()->with('success', 'Penjual ' . $user->name . ' berhasil disetujui!');
    }
    
    public function reject($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'penjual') {
            return back()->with('error', 'User bukan penjual!');
        }
        
        $user->update([
            'status_approval' => 'rejected'
        ]);
        
        return back()->with('success', 'Penjual ' . $user->name . ' ditolak.');
    }
}

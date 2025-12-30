<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $isFirstTime = !$user->profile_completed; // Cek apakah pertama kali
        return view('customer.profile', compact('user', 'isFirstTime'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $isFirstTime = !$user->profile_completed;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20', // Wajib diisi
            'address' => 'required|string', // Wajib diisi
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'profile_completed' => true, // Tandai profile sudah lengkap
        ]);

        // Jika pertama kali, redirect ke home
        if ($isFirstTime) {
            return redirect()->route('customer.home')
                ->with('success', 'Selamat datang! Profil Anda berhasil dilengkapi.');
        }

        // Jika update biasa, tetap di profile
        return back()->with('success', 'Profile berhasil diupdate!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
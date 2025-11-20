<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function showResetForm($email)
    {
        return view('auth.reset-password', compact('email'));
    }

    public function resetSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        // Update password
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        // Login otomatis
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Password berhasil direset dan Anda sudah login');
    }


    // 🟩 INI YANG HILANG! METHOD checkEmail
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        return redirect()->route('password.reset.form', ['email' => $user->email]);
    }
    public function showForm($email)
    {
        return view('auth.reset-password', compact('email'));
    }

}

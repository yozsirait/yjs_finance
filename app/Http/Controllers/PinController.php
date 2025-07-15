<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PinController extends Controller
{
    public function form()
    {
        return view('auth.pin');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        // Simulasi PIN disimpan di config, bisa juga database
        if ($request->pin === config('app.access_pin')) {
            session(['pin_verified' => true]);
            return redirect()->intended('/anggota');
        }

        return back()->withErrors(['pin' => 'PIN salah.']);
    }
}

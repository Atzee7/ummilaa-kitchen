<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function edit()
    {
        return view('profile-user', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'kode_pos'      => 'required|string|max:10',
            'alamat'        => 'required|string',
            'detail_alamat' => 'nullable|string|max:500',
            'lat'           => 'required|numeric',
            'lng'           => 'required|numeric',
        ]);

        Auth::user()->update($request->only([
            'name', 'no_telepon', 'tanggal_lahir', 'kode_pos', 'alamat', 'detail_alamat', 'lat', 'lng'
        ]));

        $redirect = session('redirect_after_profile');
        if ($redirect) {
            session()->forget('redirect_after_profile');
            return redirect($redirect)->with('success', 'Profil berhasil diperbarui!');
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
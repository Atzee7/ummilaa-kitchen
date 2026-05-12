<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompleteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $isComplete = $user->no_telepon &&
                      $user->tanggal_lahir &&
                      $user->kode_pos &&
                      $user->alamat &&
                      $user->lat &&
                      $user->lng;

        if (!$isComplete) {
            session(['redirect_after_profile' => $request->fullUrl()]);

            return redirect()->route('profile.user')
                ->with('warning', 'Harap lengkapi profil Anda terlebih dahulu sebelum melakukan pemesanan.');
        }

        return $next($request);
    }
}
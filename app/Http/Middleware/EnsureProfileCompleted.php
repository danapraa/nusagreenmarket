<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user adalah customer DAN profile belum lengkap
        if (auth()->check() && auth()->user()->isCustomer() && !auth()->user()->profile_completed) {

            // Izinkan akses hanya ke halaman edit profile, update profile, dan logout
            if (!$request->routeIs('customer.profile.*') && !$request->routeIs('logout')) {
                return redirect()->route('customer.profile.edit')
                    ->with('warning', 'Mohon lengkapi data profil Anda (Alamat & No. HP) sebelum berbelanja.');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override authenticated method
     * Redirect berdasarkan role dan status profile completion
     *
     * @param Request $request
     * @param mixed $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Jika admin, langsung ke dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Jika customer dan profile belum lengkap, ke halaman complete profile
        if ($user->isCustomer() && !$user->profile_completed) {
            return redirect()->route('customer.complete-profile')
                ->with('info', 'Silakan lengkapi data profil Anda terlebih dahulu untuk melanjutkan.');
        }

        // Jika profile sudah lengkap, ke home
        return redirect()->route('customer.home');
    }
}
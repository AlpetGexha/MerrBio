<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        //        $this->destroy($request);
        $request->authenticate();

        $request->session()->regenerate();



        $user = Auth::user();
        $user->load('roles');

        $role = $user->getRoleNames()[0];

//        return Inertia::location('https://32b8-193-254-1-161.ngrok-free.app/');

        return Inertia::location('https://merrbio-frontend.onrender.com/');

//        return Inertia::location('https://merrbio-frontend.onrender.com/');

        //        return redirect()->intended(route('dashboard', absolute: false));
//        return response()->json([
//            'user' => $user,
//            'role' => $role,
//            'token' => $user->createToken('API FOR' . $user->email . '-' . $request->device_name)->plainTextToken,
//        ]);

//                return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

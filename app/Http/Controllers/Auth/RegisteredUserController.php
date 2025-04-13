<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        if ($request->is_farmer === true) {
            $user->assignRole('farmer');
        } else {
            $user->assignRole('customer');
        }

        event(new Registered($user));

        Auth::login($user);

        //        return to_route('dashboard');

//        return response()->json([
//            'status' => 'success',
//            'message' => 'User registered successfully',
//            'user' => [
//                'name' => $user->name,
//                'email' => $user->email,
//                'is_farmer' => $user->hasRole('farmer'),
//            ],
//        ]);
//
//        if ($request->input('is_farmer') === true) {
//            return redirect('/app');
//        }


        return redirect('https://merrbio-frontend.onrender.com/');

    }

    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }
}

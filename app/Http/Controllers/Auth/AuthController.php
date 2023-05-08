<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function login() : Response
    {
        return inertia('Auth/Login');
    }

    /**
     * Authenticate user
     */
    public function authenticate(Request $request) : RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => ['required', Password::min(7)]
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Incorrect email or password!'])->onlyInput();
    }

    /**
     * Logout user
     */
    public function unauthenticate(Request $request) : RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect()->route('auth.login');
    }

    /**
     * Send an invitation
     */
    public function invite(Request $request) : RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users|unique:invites',
            'user_type' => 'required|numeric'
        ]);

        $request->merge([
            'uuid' => uniqid(),
            'user_id' => auth()->id(),
        ]);

        Invite::create($request->all());

        return back();
    }

    /**
     * Show register page for an invited user
     */
    public function register(Request $request, Invite $invite) : Response | RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('auth.expired');
        }

        return inertia('Auth/Register', ['invite' => $invite]);
    }

    /**
     * Register invited user
     */
    public function store(Request $request, Invite $invite) : RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::min(7)],
        ]);

        $request->merge([
            'type' => $invite->user_type,
        ]);

        $user = User::create($request->all());

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    /**
     * Alert user of an expired/invalid invite
     */
    public function expired() : Response
    {
        return inertia('Auth/Expired');
    }
}

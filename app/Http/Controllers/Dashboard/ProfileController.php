<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index() : Response
    {
        return inertia('Dashboard/Settings/Profile');
    }

    public function updateBasic(Request $request, User $user) : RedirectResponse
    {
        $fields = $request->all();

        if (! $user->isDirty(['email'])) {
            $fields = $request->except(['email']);
        }

        Validator::make($fields, [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users',
        ]);


        $user->fill($fields);
        $user->save();


        return back();
    }

    public function updatePassword(Request $request, User $user) : RedirectResponse
    {
        $validated = $request->validate([
            'old_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(7)]
        ]);

        $user->fill($validated);
        $user->save();

        return back();
    }
}

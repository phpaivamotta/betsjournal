<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{

    public function edit()
    {
        return view('auth.edit-profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        if (! is_null($request['password'])) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);

            $attributes['password'] = Hash::make($request->password);
        }

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->user()->id)],
        ]);

        auth()->user()->update($attributes);

        return redirect(RouteServiceProvider::HOME);
    }
}

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

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore(auth()->user()->id)
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'odd_type' => ['required', 'string']
        ]);

        $request->whenFilled('password', function ($password) use(&$attributes) {

            $attributes['password'] = Hash::make($password);

        }, function () use(&$attributes) {

            unset($attributes['password']);

        });

        auth()->user()->update($attributes);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Profile updated!');
    }
}

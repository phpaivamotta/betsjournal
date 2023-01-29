<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function store(StoreSubscriberRequest $request)
    {
        $attributes['email'] = $request->validated('subscriber-email');

        Subscriber::create($attributes);

        return back()->with('subscription-added', "Sweet! Now just check your inbox for free value bets!");
    }
}

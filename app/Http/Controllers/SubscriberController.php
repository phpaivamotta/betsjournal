<?php

namespace App\Http\Controllers;

use App\Events\NewSubscriber;
use App\Http\Requests\StoreSubscriberRequest;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(StoreSubscriberRequest $request)
    {
        $attributes['email'] = $request->validated('subscriber-email');

        $subscriber = Subscriber::create($attributes);

        // send first value bets email to subscriber
        NewSubscriber::dispatch($subscriber);

        return back()->with('subscription-added', "Sweet! You're subscribed");
    }

    public function destroy(Request $request, Subscriber $subscriber)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $subscriber->delete();

        return redirect('/')->with('success', "You were successfully unsubscribed.");
    }
}

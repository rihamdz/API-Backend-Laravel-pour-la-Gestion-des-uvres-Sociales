<?php

namespace App\Listeners;

use App\Events\OfferAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\NewOfferNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;


class SendOfferNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OfferAdded $event): void
    {
        $offer = $event->offer;

        // Retrieve all users
        $users = \App\Models\Employee::all();

        // Send email notification to each user
        foreach ($users as $user) {
            Mail::to($user->email)->send(new NewOfferNotification($offer));
        }
    
    }
}

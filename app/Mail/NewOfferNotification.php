<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOfferNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $offer; // Add a public property to hold the offer data

    /**
     * Create a new message instance.
     *
     * @param mixed $offer
     */
    public function __construct($offer)
    {
        $this->offer = $offer; // Assign the offer data to the public property
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Offer Notification')
                    ->view('emails.new_offer_notification') // Specify the email view file
                    ->with(['offer' => $this->offer])
                    ->from("SoftCraft@esi.com");// Pass the offer data to the email view
    }
}
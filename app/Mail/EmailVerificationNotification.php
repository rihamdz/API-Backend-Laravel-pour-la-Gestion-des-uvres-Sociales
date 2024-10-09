<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user ; 
    public function __construct($user)
    {
        $this->user = $user ; 
    }
    public function build()
    {
        return $this->subject('Registration')
                    ->view('emails.new_registration') // Specify the email view file
                    ->with(['user' => $this->user])
                    ->from("SoftCraft@esi.com");// Pass the offer data to the email view
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification Notification',
        );
    }

    /**
     * Get the message content definition.
     */
   

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

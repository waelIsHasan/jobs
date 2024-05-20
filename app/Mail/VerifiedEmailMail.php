<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Ichtrojan\Otp\Otp;

class VerifiedEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $otp;
    private $email;
    public function __construct($email)
    {
        $this->email = $email;
        $this->otp = new Otp();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verified Email Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $otp = $this->otp->generate($this->email , 'numeric' , 6 , 60);
        return new Content(
            view: 'verification',
            with:[
                "otp"=>$otp->token
            ]
        );
    }

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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TwoFactorStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $enabled;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $enabled)
    {
        $this->user = $user;
        $this->enabled = $enabled;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $status = $this->enabled ? 'enabled' : 'disabled';

        return $this->subject("Two-Factor Authentication {$status} for your account")
                    ->view('emails.two_factor_status');
    }
}

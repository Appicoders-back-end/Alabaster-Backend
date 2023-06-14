<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;
    public $isForgotPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $code, $isForgotPassword = false)
    {
        $this->user = $user;
        $this->code = $code;
        $this->isForgotPassword = $isForgotPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->isForgotPassword) {
            return $this->markdown('mail.forgot-password', ['user' => $this->user, 'code' => $this->code])->subject("Forgot Password");
        }
        return $this->markdown('mail.user-created', ['user' => $this->user, 'code' => $this->code]);
    }
}

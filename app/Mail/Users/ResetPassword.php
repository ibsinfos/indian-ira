<?php

namespace IndianIra\Mail\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The ForgotPassword instance holder.
     *
     * @var  \IndianIra\ForgotPassword
     */
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@indianira.com', config('app.name'))
                    ->markdown('emails.users.forgot_password')
                    ->subject(config('app.name'). ': Reset Password')
                    ->with(['data' => $this->password]);
    }
}

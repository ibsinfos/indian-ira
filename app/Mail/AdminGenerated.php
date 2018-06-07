<?php

namespace IndianIra\Mail;

use IndianIra\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminGenerated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Super Admin User instance
     *
     * @var  \IndianIra\User
     */
    protected $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@indianira.in', config('app.name'))
                    ->subject(config('app.name'). ': Super Administrator Generated')
                    ->markdown('emails.super_admin_generated')
                    ->with(['admin' => $this->admin]);
    }
}

<?php

namespace IndianIra\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The authenticated user.
     *
     * @var  \IndianIra\User
     */
    private $user;

    /**
     * The order that was placed.
     *
     * @var  \Illuminate\Database\Eloquent\Collection
     */
    private $orders;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $orders)
    {
        $this->user   = $user;
        $this->orders = $orders;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@indianira.com', config('app.name'))
                    ->view('emails.orders_placed')
                    ->subject('Order Placed: '. $this->orders->first()->order_code)
                    ->with(['user' => $this->user, 'orders' => $this->orders]);
    }
}

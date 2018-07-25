<?php

namespace IndianIra\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductEnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Enquire Product instance holder.
     *
     * @var  \IndianIra\EnquireProduct
     */
    public $enquiry = null;

    /**
     * The enquiry form data.
     *
     * @var  array
     */
    public $enquireData = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enquiry, $enquireData)
    {
        $this->enquiry     = $enquiry;
        $this->enquireData = $enquireData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@indianira.com', config('app.name'))
                    ->view('emails.products_enquiry_received')
                    ->subject('Product Enquiry Received. Code: ' . $this->enquiry->code)
                    ->with([
                        'enquiry'     => $this->enquiry,
                        'enquireData' => $this->enquireData
                    ]);
    }
}

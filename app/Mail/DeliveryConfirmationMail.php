<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deliveryData;

    public function __construct($deliveryData)
    {
        $this->deliveryData = $deliveryData;
    }

    public function build()
    {
        return $this->subject('Delivery Confirmation')
                    ->view('emails.delivery-confirmation')
                    ->with('deliveryData', $this->deliveryData);
    }
}
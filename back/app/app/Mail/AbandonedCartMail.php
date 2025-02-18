<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonedCartMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $customerName;
    public $cartItems;
    public $totalPrice;

    public function __construct($customerName, $cartItems, $totalPrice)
    {
        $this->customerName = $customerName;
        $this->cartItems = $cartItems;
        $this->totalPrice = $totalPrice;
    }

    public function build()
    {
        return $this->view('emails.abandoned_cart')
            ->subject('Váš opuštěný košík')
            ->with([
                'customerName' => $this->customerName,
                'cartItems' => $this->cartItems,
                'totalPrice' => $this->totalPrice,
            ]);
    }
}

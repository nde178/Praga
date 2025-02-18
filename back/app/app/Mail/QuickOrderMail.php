<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuickOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $product;
    public $quantity;
    public $totalPrice;
    public $phone;
    public $email;

    public function __construct($customerName, $product, $quantity, $totalPrice, $phone, $email)
    {
        $this->customerName = $customerName;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->totalPrice = $totalPrice;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Ваш быстрый заказ принят')
            ->view('emails.quick_order');
    }
}

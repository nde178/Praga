<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbandonedCartMail;

class SendAbandonedCartReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerName;
    protected $email;
    protected $cartItems;
    protected $totalPrice;

    public function __construct($customerName, $email, $cartItems, $totalPrice)
    {
        $this->customerName = $customerName;
        $this->email = $email;
        $this->cartItems = $cartItems;
        $this->totalPrice = $totalPrice;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new AbandonedCartMail($this->customerName, $this->cartItems, $this->totalPrice));
    }
}

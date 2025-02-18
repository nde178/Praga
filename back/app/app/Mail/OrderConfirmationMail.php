<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use SerializesModels;

    public $order;

    /**
     * Создает экземпляр класса.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Построение сообщения.
     */
    public function build(): OrderConfirmationMail
    {
        return $this->subject('Potvrzení objednávky')
            ->view('emails.order-confirmation') // Укажите путь к шаблону
            ->with(['order' => $this->order]); // Передаем данные в шаблон
    }
}

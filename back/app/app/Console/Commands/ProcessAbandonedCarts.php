<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessAbandonedCarts extends Command
{
    protected $signature = 'process:abandoned-carts';
    protected $description = 'Send reminder emails for abandoned carts';

    public function handle()
    {
        // Получаем все корзины, которые были созданы более 1 часа назад и не завершены
        $carts = Cart::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours(1))
            ->get();

        foreach ($carts as $cart) {
            // Проверяем, было ли уже отправлено письмо
            $emailSent = AbandonedCartEmail::where('cart_id', $cart->id)->first();

            if (!$emailSent) {
                // Отправляем письмо
                Mail::to($cart->email)->send(new \App\Mail\AbandonedCartReminder($cart));

                // Сохраняем информацию об отправке
                AbandonedCartEmail::create([
                    'cart_id' => $cart->id,
                    'email_sent' => true,
                    'sent_at' => Carbon::now()
                ]);

                // Обновляем статус корзины на "abandoned"
                $cart->update(['status' => 'abandoned']);
            }
        }

        $this->info('Abandoned carts processed successfully.');
    }
}

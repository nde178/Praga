<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potvrzení objednávky</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { padding: 20px; max-width: 600px; margin: 0 auto; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #ffffff; border: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-table th, .order-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .order-table th { background-color: #f8f9fa; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Potvrzení objednávky</h1>
    </div>
    <div class="content">
        <p>Dobrý den, {{ $order->customer_name }},</p>
        <p>Děkujeme za Vaši objednávku! Podrobnosti:</p>
        <table class="order-table">
            <tr>
                <th>Produkt</th>
                <th>Počet</th>
                <th>Cena</th>
            </tr>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price, 2) }} Kč</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>Celková částka</strong></td>
                <td><strong>{{ number_format($order->total_price, 2) }} Kč</strong></td>
            </tr>
        </table>
        <p>Způsob platby: {{ $order->payment_method }}</p>
        <p>Způsob doručení: {{ $order->delivery_method }}</p>
        <p>Děkujeme, že jste si vybrali náš obchod!</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Váš obchod
    </div>
</div>
</body>
</html>


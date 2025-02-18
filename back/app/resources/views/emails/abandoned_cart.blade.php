<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opuštěný košík</title>
</head>
<body>
<!-- Кнопка перехода в магазин -->
<p><a href="https://obchod.tanatar.cz" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none;">Vstoupit do e-shopu</a></p>
<table width="100%">
    <tr>
        <td>
            <p>Dobrý den, {{ $customerName }},</p>
            <p>U nás zůstal opuštěný košík. Objednávku můžete kdykoliv dokončit. Obsah košíku:</p>

            <!-- Таблица с содержимым корзины -->
            <table width="100%" border="1" cellpadding="10" cellspacing="0">
                <thead>
                <tr>
                    <th>Kód produktu</th>
                    <th>Název produktu</th>
                    <th>Cena</th>
                    <th>Množství</th>
                    <th>Celková cena</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td><img src="{{ $item['main_image'] }}" alt="{{ $item['name'] }}" style="width: 50px; height: auto;"></td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price'], 2) }} Kč</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['total_price'], 2) }} Kč</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Общая цена -->
            <p>Celková cena: <strong>{{ number_format($totalPrice, 2) }} Kč</strong></p>
        </td>
    </tr>
</table>
</body>
</html>

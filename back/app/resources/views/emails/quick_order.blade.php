<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение быстрого заказа</title>
</head>
<body>
<h1>Здравствуйте, {{ $customerName }}!</h1>
<p>Спасибо за ваш заказ! Мы получили ваш быстрый заказ и приступили к его обработке.</p>

<h2>Детали заказа</h2>
<table width="100%" border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Продукт</th>
        <th>Количество</th>
        <th>Цена за единицу</th>
        <th>Итоговая цена</th>
    </tr>
    <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $quantity }}</td>
        <td>{{ number_format($product->price, 2) }} Kč</td>
        <td>{{ number_format($totalPrice, 2) }} Kč</td>
    </tr>
</table>

<h3>Контактная информация</h3>
<p><strong>Телефон:</strong> {{ $phone }}</p>
<p><strong>Email:</strong> {{ $email }}</p>

<p>Мы свяжемся с вами для подтверждения заказа. Спасибо за выбор нашего магазина!</p>

<p>С уважением, <br> Команда магазина</p>
</body>
</html>

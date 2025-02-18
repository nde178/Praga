<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать!</title>
</head>
<body>
<h1>Здравствуйте, {{ $name }}!</h1>
<p>Вы успешно зарегистрированы в нашем интернет-магазине.</p>
<p>Ваши учетные данные для входа:</p>
<ul>
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Пароль:</strong> {{ $password }}</li>
</ul>
<p>Пожалуйста, сохраните эти данные в надежном месте.</p>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Sikeres regisztráció</title>
</head>
<body>
    <h1>Kedves {{ $user->name }}!</h1>
    <p>Köszönjük, hogy regisztráltál az oldalunkon!</p>
    <p>Üdvözlettel,<br>{{ config('webshop') }}</p>
</body>
</html>
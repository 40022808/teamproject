<!DOCTYPE html>
<html>
<head>
    <title>Foglalás megerősítése</title>
</head>
<body>
    <h1>Foglalás megerésítése</h1>
    <p>Kedves Felhasználó,</p>
    <p>jKöszönjük a foglalásokat! Itt vannak a részletek:</p>
    <ul>
        <li><strong>Date:</strong> {{ $bookingData['date'] }}</li>
        <li><strong>Time:</strong> {{ $bookingData['time'] }}</li>
        <li><strong>Gender:</strong> {{ $bookingData['gender'] }}</li>
    </ul>
    <p>Már várjuk jelenkezését!</p>
</body>
</html>
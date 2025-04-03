<!DOCTYPE html>
<html>
<head>
    <title>Szállítási Visszaigazolás</title>
</head>
<body>
    <h1>Szállítási Visszaigazolás</h1>
    <p>Kedves {{ $deliveryData['fullName'] }}!</p>
    <p>Köszönjük, hogy megadta szállítási adatait. Az alábbiakban találja a részleteket:</p>
    <ul>
        <li><strong>Teljes név:</strong> {{ $deliveryData['fullName'] }}</li>
        <li><strong>Cím:</strong> {{ $deliveryData['address'] }}</li>
        <li><strong>Házszám:</strong> {{ $deliveryData['houseNumber'] }}</li>
        <li><strong>Város:</strong> {{ $deliveryData['city'] }}</li>
        <li><strong>Irányítószám:</strong> {{ $deliveryData['postalCode'] }}</li>
        <li><strong>Telefonszám:</strong> {{ $deliveryData['phone'] }}</li>
    </ul>
    <p>Rendelését készpénzzel vagy bankkártyával tudja kifizetni a szállítás időpontjában.</p>
    <p>Köszönjük rendelését!</p>
    <p>Üdvözlettel,</p>
    <p>Barber Shop Csapat :3</p>
    <p>Megjegyzés: Ez egy automatikus üzenet. Kérjük, ne válaszoljon erre az e-mailre.</p>
</body>
</html>
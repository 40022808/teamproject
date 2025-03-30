<!DOCTYPE html>
<html>
<head>
    <title>Delivery Confirmation</title>
</head>
<body>
    <h1>Delivery Confirmation</h1>
    <p>Dear {{ $deliveryData['fullName'] }},</p>
    <p>Thank you for providing your delivery details. Here are the details:</p>
    <ul>
        <li><strong>Full Name:</strong> {{ $deliveryData['fullName'] }}</li>
        <li><strong>Address:</strong> {{ $deliveryData['address'] }}</li>
        <li><strong>House Number:</strong> {{ $deliveryData['houseNumber'] }}</li>
        <li><strong>City:</strong> {{ $deliveryData['city'] }}</li>
        <li><strong>Postal Code:</strong> {{ $deliveryData['postalCode'] }}</li>
        <li><strong>Phone Number:</strong> {{ $deliveryData['phone'] }}</li>
    </ul>
    <p>We will process your delivery shortly.</p>
</body>
</html>
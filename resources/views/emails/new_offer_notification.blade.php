<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Offer Notification</title>
</head>
<body>
    <h1>New Offer Notification</h1>
    <p>Hello,</p>
    <p>A new offer has been added:</p>
    <ul>
        <li>Title: {{ $offer->title }}</li>
        <li>Description: {{ $offer->description }}</li>
        <!-- Add more offer details here -->
    </ul>
    <p>Thank you.</p>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Validation</title>
</head>
<body>
    <h1>Email Validation</h1>
    <p>
        Hello {{ $user->email }},<br><br>
        
        Thank you for registering. Please use the following confirmation token to validate your email address:<br><br>

        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Confirmation Token:</strong> {{ $user->confirmation_token }}<br><br>

        If you didn't request this, you can safely ignore this email.
    </p>
    <p>Best regards,<br>
        Your Application Team
    </p>
</body>
</html>

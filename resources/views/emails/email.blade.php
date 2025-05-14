<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body>
    <h2>Hello {{ $user->name_en }},</h2>
    <p>Click the button below to verify your email:</p>

    <a href="{{ route('verify', ['id' => $user->id]) }}" 
       style="display:inline-block; padding:10px 20px; background-color:#007bff; color:#fff; text-decoration:none; border-radius:5px;">
        Verify Email
    </a>

    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
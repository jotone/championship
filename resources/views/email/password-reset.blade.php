<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Password Reset</title>
</head>
<body>

<div>
  <p>Dear, {{ $name }}</p>
  <p>If you never send this request, just leave.</p>
  <p></p>
  <p>To proceed the password reset process, please, follow <a href="{{ route('password-reset.form', $token) }}">this link</a></p>
  <p>If the link is not clickable, just copy the text below into your browser search bar.</p>
  <p>{{ route('password-reset.form', $token) }}</p>
</div>
</body>
</html>
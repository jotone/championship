<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Email Confirmation</title>
</head>
<body>

<div>
  <p>Dear, {{ $name }}</p>
  <p>You are registered at {{ config('app.name') }}.</p>
  <p>
    To confirm your email, <a href="{{ route('registration.confirmation', $token) }}">follow the link</a>
  </p>
  <p>If the link is not clickable, just copy the text below into your browser search bar.</p>
  <p>{{ route('registration.confirmation', $token) }}</p>
</div>
</body>
</html>
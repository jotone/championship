<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>

  @vite([
    'resources/css/reset.scss',
    'resources/css/main/app.scss'
  ])

  @yield('styles')
</head>
<body>
<header>
  <nav class="top-menu">
    <ul></ul>
  </nav>
  <div class="login-form-menu">
    <ul>
      @auth
        <li>
          <a href="{{ route('auth.logout') }}">Sign Out</a>
        </li>
      @else
        <li data-show="login-form">
          <span>Sign In</span>
        </li>
        <li>
          <a href="{{ route('registration.index') }}">Sign Up</a>
        </li>
      @endauth
    </ul>
  </div>
</header>

<form class="login-form" action="{{ route('auth.login') }}" method="POST">
  @csrf
  <div>
    <input name="email" type="email" placeholder="Email&hellip;">
  </div>
  <div>
    <input name="password" type="password" placeholder="Password&hellip;">
  </div>
  <div>
    <button type="submit">YARRR</button>
    <a href="{{ route('password-reset.index') }}" style="color: white">Forgot password</a>
  </div>

</form>

@yield('content')

@vite([
  'resources/js/main/app.js'
])
</body>
</html>
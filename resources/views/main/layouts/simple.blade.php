<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>

  <link rel="stylesheet" href="/css/reset.css">
  <link rel="stylesheet" href="/css/fontawesome.css">
  <link rel="stylesheet" href="/css/solid.css">
  <link rel="stylesheet" href="/css/app.css">

  <script src="/js/jquery.js"></script>
  @yield('styles')
</head>


<body>
<header>
  <nav class="login-form-menu">
    <ul>
      @auth
        <li>
          <a href="#">Good luck, {{ Auth::user()->name }}</a>
        </li>
        <li>
          <a href="{{ route('auth.logout') }}">Sign Out</a>
        </li>
      @else
        <li data-show="login-form">
          <span>Sign In</span>
        </li>

        @if($settings['registration_enable']->converted_value)
          <li>
            <a href="{{ route('registration.index') }}">Sign Up</a>
          </li>
        @endisset
      @endauth
    </ul>
  </nav>
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

@include('main.layouts.header')

@yield('content')

@yield('scripts')

</body>
</html>

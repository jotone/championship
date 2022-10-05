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

<div class="banner-wrap" style="background-image: url('/images/header.jpg')">
  <nav class="site-menu-wrap">
    <ul>
      @auth
      <li><a href="{{ route('user.form.index') }}">Anketa</a></li>
      @endauth
      <li><a href="#">Forum</a></li>
      <li><a href="#">Svodnaja</a></li>
      <li><a href="#">Rules</a></li>
      <li><a href="#">Groups</a></li>
      <li><a href="#">Raspisanie</a></li>
      <li><a href="#">Help</a></li>
    </ul>
  </nav>
</div>

<div class="content-wrap">
  @include('main.layouts.participant')

  <main class="page-content-wrap">
    @yield('content')
  </main>

  @include('main.layouts.real-scores')
</div>

@vite([
  'resources/js/main/app.js'
])
</body>
</html>
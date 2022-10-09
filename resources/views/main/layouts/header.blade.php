<header>
  <nav class="login-form-menu">
    <ul>
      @auth
        <li>
          <a href="{{ route('user.profile.show') }}">Good luck, {{ Auth::user()->name }}</a>
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
      <li><a href="{{ route('home.index') }}">Home</a></li>
      @auth
        <li><a href="{{ route('user.form.show') }}">Anketa</a></li>
      @endauth
      <li><a href="#">Forum</a></li>
      <li><a href="#">Svodnaja</a></li>
      <li><a href="#">Rules</a></li>
      <li><a href="#">Groups</a></li>
      <li><a href="#">Raspisanie</a></li>
      <li><a href="#">Help</a></li>
    </ul>
  </nav>


  <div class="messages-wrap">
    <ul>
      @if ($errors->count() || !empty($messages))
        @foreach($errors->all() as $msg)
          <li class="error">
            <div class="close"><i class="fas fa-times-circle"></i></div>
            <div class="message-text">{{ $msg }}</div>
          </li>
        @endforeach

        @if(!empty($messages))
          @foreach($messages as $type => $message_list)
            @foreach($message_list as $msg)
              <li class="{{ $type }}">
                <div class="close"><i class="fas fa-times-circle"></i></div>
                <div class="message-text">{{ $msg }}</div>
              </li>
            @endforeach
          @endforeach
        @endif
      @endif
    </ul>
  </div>
</div>
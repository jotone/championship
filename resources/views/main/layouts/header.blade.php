<header>
  @isset($setup['logo_img_url'])
    <a class="logo-image-wrap" href="/">
      <img src="{{ $setup['logo_img_url']->value }}" alt="">
    </a>
  @else
    <div></div>
  @endisset
  <nav class="login-form-menu">
    <ul>
      @auth
        <li>
          <a href="{{ route('user.profile.show') }}">Бажаємо успіху, {{ Auth::user()->name }}</a>
        </li>
        <li>
          <a href="{{ route('auth.logout') }}">Вийти</a>
        </li>
      @else
        <li data-show="login-form">
          <span>Увійти</span>
        </li>

        @if($setup['registration_enable']->converted_value)
          <li>
            <a href="{{ route('registration.index') }}">Зареєструватися</a>
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
    <input name="password" type="password" placeholder="Пароль&hellip;">
  </div>
  <div class="form-buttons">
    <button class="btn regular" type="submit">Увійти</button>
    <a href="{{ route('password-reset.index') }}">Забули пароль</a>
  </div>
</form>

<div
  class="banner-wrap"
  @if(!empty($setup['header_img_url']->value)) style="background-image: url({{ $setup['header_img_url']->value }})" @endif>
  <nav class="site-menu-wrap">
    <ul>
      <li><a href="{{ route('home.index') }}">Головна</a></li>
      @auth
        <li><a href="{{ route('user.form.show') }}">Анкета</a></li>
      @endauth
      @if($setup['forum_enable']->converted_value)
        <li><a href="{{ route('forum.index') }}">Форум</a></li>
      @endif
      @if($setup['summary_enable']->converted_value)
        <li><a href="{{ route('summary.index') }}">Зведена таблиця</a></li>
      @endif
      <li><a href="{{ route('rules.index') }}">Правила</a></li>
      <li><a href="{{ route('groups.index') }}">Групи</a></li>
      <li><a href="{{ route('schedule.index') }}">Розклад</a></li>
      <li><a href="{{ route('help.index') }}">Допомога</a></li>
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
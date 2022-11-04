<header>
  @isset($setup['logo_img_url'])
    <a class="logo-image-wrap" href="{{ route('admin.index') }}">
      <img src="{{ $setup['logo_img_url']->value }}" alt="">
    </a>
  @else
    <div></div>
  @endisset

  <ul>
    <li>
      <a href="#">
        <span>Привіт, {{ Auth::user()->name }}</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fas fa-bell"></i>
      </a>
    </li>
    <li>
      <a href="{{ route('auth.logout') }}">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </li>
  </ul>
</header>
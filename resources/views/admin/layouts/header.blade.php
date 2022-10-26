<header>
  <ul>
    <li>
      <a href="#">
        <span>Привіт, {{ Auth::user()->name }}</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fas fa-envelope"></i>
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
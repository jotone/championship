<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @isset($jwt_token)
    <meta name="jwt-token" content="{{ $jwt_token }}">
  @endisset

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/reset.css">
  <link rel="stylesheet" href="/css/fontawesome.css">
  <link rel="stylesheet" href="/css/solid.css">
  <link rel="stylesheet" href="/css/admin/app.css">

  @isset($setup['fav_icon'])
    <link rel="icon" href="/favicon/favicon.ico" sizes="any">
    @if(pathinfo($setup['fav_icon']->value, PATHINFO_EXTENSION) == 'svg')
        <link rel="icon" href="/favicon/icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/favicon/apple-touch-icon.png">
        <link rel="manifest" href="/favicon/manifest.webmanifest">
    @endif
  @endisset

  @yield('styles')

  <script src="/js/jquery.js"></script>

  <title>{{ $setup['site_title']->value }}</title>
</head>

<body>
@include('admin.layouts.header')

<div class="center-box">
  <aside>
    <nav class="side-menu">
      @include('admin.layouts.side-menu')
    </nav>
  </aside>

  <main>
    <div class="page-top-elements">
      <div class="page-title"><h1>{!! $title !!}</h1></div>

      <ul class="breadcrumbs-wrap">
        @if(count($breadcrumbs) > 1)
          @foreach($breadcrumbs as $item)
            <li>
              @isset($item['url'])
                <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
              @else
                <span>{{ $item['name'] }}</span>
              @endisset
            </li>
          @endforeach
        @endif
      </ul>
    </div>

    @yield('content')
  </main>
</div>

<ul class="notifications-wrap"></ul>

<div class="overlay">
  <img class="preload" src="/images/preload.svg" alt="Loading&hellip;">

  @yield('popup')
</div>

<script src="/js/admin/main.js"></script>

@yield('scripts')

<script src="/js/admin/app.js"></script>
</body>
</html>
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
  @vite([
    'resources/css/reset.scss',
    'resources/css/font-awesome/fontawesome.scss',
    'resources/css/font-awesome/solid.scss',
    'resources/css/admin/app.scss',
    'resources/js/admin/main.js'
  ])
  <title>Document</title>
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

@yield('scripts')

</body>
</html>
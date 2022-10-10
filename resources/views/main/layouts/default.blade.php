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
    'resources/css/font-awesome/fontawesome.scss',
    'resources/css/font-awesome/solid.scss',
    'resources/css/main/app.scss'
  ])

  @yield('styles')
</head>
<body>

@include('main.layouts.header')

<div class="main-container">
  <div class="content-wrap">
    @include('main.layouts.participant')

    <main class="page-content-wrap">
      @yield('content')
    </main>

    @include('main.layouts.real-scores')
  </div>
</div>

@vite(['resources/js/main/app.js'])
@yield('scripts')
</body>
</html>
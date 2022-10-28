<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  @isset($page_data)
    @if(!empty($page_data->meta_description))
      <meta name="description" content="{{ $page_data->meta_description }}">
    @endif
    @if(!empty($page_data->meta_keywords))
      <meta name="keywords" content="{{ $page_data->meta_keywords }}">
    @endif
  @endisset
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $setup['meta_title'] ?? $setup['site_title'] }}</title>

  <link rel="stylesheet" href="/css/reset.css">
  <link rel="stylesheet" href="/css/fontawesome.css">
  <link rel="stylesheet" href="/css/solid.css">
  <link rel="stylesheet" href="/css/app.css">

  <script src="/js/jquery.js"></script>
  @yield('styles')
</head>

<body>
@include('main.layouts.header')

@yield('content')

@yield('scripts')

</body>
</html>

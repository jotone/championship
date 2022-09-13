@extends('main.layouts.default')

@section('styles')
  @vite([
    'resources/css/font-awesome/fontawesome.scss',
    'resources/css/font-awesome/solid.scss'
  ])
@endsection

@section('content')
  <div class="registration-form">
    <div class="thank-you-wrap">
      <i class="fas fa-check-circle success"></i>
    </div>

    <div class="thank-you-text">
      <p>Your Password was successfully changed.</p>
      <p>Now go on <a href="{{ route('home.index') }}">main page</a> and proceed the authorization.</p>
    </div>
  </div>
@endsection
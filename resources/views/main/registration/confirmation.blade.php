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
      @if($expired)
        <i class="fas fa-times-circle"></i>
      @else
        <i class="fas fa-check-circle success"></i>
      @endif
    </div>

    <div class="thank-you-text">
      @if($expired)
        <p>Sorry your confirmation token has been expired.</p>
        <p>We send you a new letter to confirm your email.</p>
      @else
        <p>Email Confirmed and you are authorized</p>
        <p>Now you can go to <a href="{{ route('home.index') }}">to the main page</a> and continue your exploration</p>
      @endif
    </div>
  </div>
@endsection
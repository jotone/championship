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
      <p>Thank You for your registration.</p>
      <p>We send You a letter to confirm your email on <a href="mailto:{{ $email }}">{{ $email }}</a>.</p>
      <p>To continue, follow the link in the email we sent you .</p>
    </div>
  </div>
@endsection
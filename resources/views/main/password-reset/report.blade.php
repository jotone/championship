@extends('main.layouts.default')

@section('styles')
  @vite([
    'resources/css/font-awesome/fontawesome.scss',
    'resources/css/font-awesome/solid.scss'
  ])
@endsection

@section('content')
  <div class="registration-form">
    <div class="thank-you-text">
      <p>{{ $message }}</p>
    </div>
  </div>
@endsection


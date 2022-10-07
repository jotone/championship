@extends('main.layouts.simple')

@section('content')
  @auth
    <p>You are already registered</p>
  @else
    <form class="registration-form" method="post" action="{{ route('registration.store') }}">
      @csrf
      <div>
        <input name="name" autocomplete="off" placeholder="Enter your name&hellip;">
      </div>
      <div>
        <input name="email" autocomplete="off" type="email" placeholder="Enter your email to register&hellip;">
      </div>
      <div>
        <input name="password" autocomplete="off" type="password" placeholder="Enter your password&hellip;">
      </div>
      <div>
        <input name="confirmation" autocomplete="off" type="password" placeholder="Confirm your password&hellip;">
      </div>

      <div>
        <button type="submit">
          Sign Up
        </button>
      </div>
    </form>
  @endauth
@endsection
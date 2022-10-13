@extends('main.layouts.simple')

@section('content')
  <div class="registration-form">
    <div class="thank-you-text">
      <p>To restore your password, please enter your email below</p>
      <form action="{{ route('password-reset.store') }}" method="post">
        @csrf

        <div>
          <input
            style="min-width: 320px"
            type="email"
            name="email"
            autocomplete="off"
            placeholder="Insert your account email here&hellip;"
          >
        </div>

        <div>
          <button type="submit">
            YARRR
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection


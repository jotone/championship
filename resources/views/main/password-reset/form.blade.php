@extends('main.layouts.simple')

@section('styles')
  @vite([
    'resources/css/font-awesome/fontawesome.scss',
    'resources/css/font-awesome/solid.scss'
  ])
@endsection

@section('content')
  <div class="registration-form">
    <div class="thank-you-text">
      @if(time() > $reset->created_at->addWeek()->timestamp)
        <p>Password reset token is expired.</p>
        <p>
          To resume the password recovery process, <a href="{{ route('password-reset.index') }}">follow the link</a>
        </p>
      @else
        <p>Please set a new password for your account, then confirm it:</p>
        <form action="{{ route('password-reset.update', $reset->token) }}" method="post">
          @csrf

          <div>
            <input name="password" type="password" placeholder="Enter your password&hellip;">
          </div>
          <div>
            <input name="confirmation" type="password" placeholder="Confirm password&hellip;">
          </div>
          <div>
            <button type="submit">YARRR</button>
          </div>
        </form>
      @endif
    </div>
  </div>
@endsection


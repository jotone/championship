@extends('main.layouts.default')

@section('scripts')
  @vite(['resources/js/main/user-form/app.js'])
@endsection

@section('content')
  <form
    action="{{ route('user.form.store', md5($competition->id)) }}"
    method="post"
  >
    @csrf
    <input type="hidden" name="_json" value="{{ base64_encode($jwt_token) }}">

    <div id="userForm" data-routes="{{ base64_encode(json_encode($routes)) }}"></div>

    <div class="content-block-wrap flex-c">
      <button type="submit" class="btn regular">
        <span>Отправить анкету</span>
      </button>
    </div>
  </form>
@endsection
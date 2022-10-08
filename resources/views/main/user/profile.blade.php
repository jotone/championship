@extends('main.layouts.default')

@section('styles')
  @vite(['node_modules/froala-editor/css/froala_editor.min.css',])
@endsection

@section('scripts')
  @vite(['resources/js/main/profile.js'])
@endsection

@section('content')
  <form
    action="{{ route('user.profile.update') }}"
    method="post"
  >
    @csrf
    @method('PUT')

    <div class="page-heading">
      <h1>РЕДАГУВАННЯ ПРОФІЛЮ "{{ $user->name }}"</h1>
    </div>

    <fieldset class="form-fieldset">
      <legend>Змінити зображення</legend>

      <div class="image-upload-wrap">
        <div class="image-upload-preview">
          @if(!empty($user->img_url))
            <img src="{{ $user->img_url }}" alt="">
          @endif
        </div>
        <div class="image-upload-controls">
          <input name="img_url" type="file" style="display: none" accept="image/jpeg,image/png">
          <button name="uploadImage" type="button" class="btn regular">
            Загрузити
          </button>
        </div>
      </div>
    </fieldset>

    <fieldset class="form-fieldset">
      <legend>Зміна паролю</legend>

      <div class="form-row">
        <label>
          <span class="caption">Старий пароль</span>
          <input name="old_pwd" class="form-input" type="password">
        </label>
      </div>
      <div class="form-row">
        <label>
          <span class="caption">Новий пароль</span>
          <input name="password" class="form-input" type="password">
        </label>
      </div>
      <div class="form-row">
        <label>
          <span class="caption">Підтвердіть новий пароль</span>
          <input name="confirmation" class="form-input" type="password">
        </label>
      </div>
    </fieldset>

    <fieldset class="form-fieldset">
      <legend>Додати інформацію про себе</legend>

      <div class="form-row">
        <div class="editor" data-name="info">{{ $user->info }}</div>
      </div>
    </fieldset>

    <div class="buttons-wrap">
      <button class="btn success">
        Застосувати зміни
      </button>
    </div>
  </form>
@endsection
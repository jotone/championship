@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.users.update', $model->id) : route('api.users.store') }}"
      data-xhr
      data-msg="User.name"
      method="POST"
    >
      @isset($model)
        @method('PUT')
      @endisset
      @csrf

      <div class="row col-100">
        <fieldset class="col-50" @if(isset($model) && $user->role->level > $model->role->level) disabled @endif>
          <legend>Основна інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>Ім'я користувача:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="name"
                placeholder="Ім'я користувача&hellip;"
                required
                value="{{ $model->name ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Email:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="email"
                placeholder="Email&hellip;"
                required
                type="email"
                value="{{ $model->email ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Пароль:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="password"
                placeholder="Пароль&hellip;"
                @if(!isset($model)) required @endif
                type="password"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Підтвердження паролю:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="confirmation"
                placeholder="Підтвердження паролю&hellip;"
                @if(!isset($model)) required @endif
                type="password"
              >
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50" @if(isset($model) && $user->role->level > $model->role->level) disabled @endif>
          <legend>Додаткова інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>Роль:</span>

              <select class="form-select" name="role_id">
                @foreach($roles as $role)
                  @if($user->role->level <= $role->level)
                    <option
                      value="{{ $role->id }}"
                      {{ (isset($model) && $role->id == $model->role_id) || (!isset($model) && $role->slug == 'regular') ? 'selected' : '' }}
                    >
                      {{ $role->name }}
                    </option>
                  @endif
                @endforeach
              </select>
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Зображення:</span>
            </label>

            @include('admin.layouts.image-upload', [
              'model' => $model ?? null,
              'field' => 'img_url',
              'accept' => 'image/jpeg,image/png'
            ])
          </div>
        </fieldset>
      </div>

      @if((isset($model) && $user->role->level <= $model->role->level) || !isset($model))
        <div class="row">
          <button type="submit" class="btn success">
            Зберегти
          </button>
        </div>
      @endif
    </form>
  </div>
@endsection

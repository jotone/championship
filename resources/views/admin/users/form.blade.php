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
        <fieldset class="col-50">
          <legend>Main information</legend>

          <div class="form-row">
            <label class="caption">
              <span>User name:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="name"
                placeholder="User name&hellip;"
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
              <span>Password:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="password"
                placeholder="Password&hellip;"
                @if(!isset($model)) required @endif
                type="password"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Password confirmation:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="confirmation"
                placeholder="Password confirmation&hellip;"
                @if(!isset($model)) required @endif
                type="password"
              >
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Etc</legend>

          <div class="form-row">
            <label class="caption">
              <span>Role:</span>

              <select class="form-select" name="role_id">
                @foreach($roles as $role)
                  <option
                    value="{{ $role->id }}"
                    @if(isset($model) && $role->id == $model->role_id) selected @endif
                  >
                    {{ $role->name }}
                  </option>
                @endforeach
              </select>
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Image:</span>
            </label>

            <div class="image-upload-wrap">
              <div class="image-upload-preview">
                @if(isset($model) && !empty($model->img_url))
                  <img src="{{ $model->img_url }}" alt="No image&hellip;">
                @endif
              </div>
              <div class="buttons-wrap">
                <input name="img_url" type="file" style="display: none" accept="image/jpeg,image/png">
                <button name="upload" class="btn" type="button">
                  <span>Upload</span>
                </button>
                <button name="clear" class="btn cancel" type="button">
                  <span>Clear</span>
                </button>
              </div>
            </div>
          </div>
        </fieldset>
      </div>

      <div class="row">
        <button type="submit" class="btn success">
          Save
        </button>
      </div>
    </form>
  </div>
@endsection
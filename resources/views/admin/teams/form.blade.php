@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.teams.update', $model->id) : route('api.teams.store') }}"
      data-xhr
      data-msg="Team.ua"
      method="POST"
    >
      @isset($model)
        @method('PUT')
      @endisset
      @csrf

      <div class="row col-100">
        <fieldset class="col-30">
          <legend>Основна інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>Українська назва:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                data-slug="input.en"
                name="ua"
                placeholder="Українська назва&hellip;"
                required
                value="{{ $model->ua ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Міжнародна Назва:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="en"
                placeholder="Міжнародна Назва&hellip;" required
                value="{{ $model->en ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Країна:</span>

              <select name="country_id" class="form-select col-100">
                @foreach($countries as $country)
                  <option
                    value="{{ $country->id }}"
                    @if(isset($model) && $model->country_id == $country->id) selected @endif
                  >
                    {{ $country->ua }}
                  </option>
                @endforeach
              </select>
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Зображення</legend>

          <div class="form-row">
            <label class="caption">
              <span>Завантажити Зображення:</span>
            </label>

            @include('admin.layouts.image-upload', ['model' => $model ?? null, 'field' => 'img_url'])
          </div>
        </fieldset>
      </div>

      <div class="row">
        <button type="submit" class="btn success">
          Зберегти
        </button>
      </div>
    </form>
  </div>
@endsection
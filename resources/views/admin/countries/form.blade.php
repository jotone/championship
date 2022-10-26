@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.countries.update', $model->id) : route('api.countries.store') }}"
      data-xhr
      data-msg="Країна.ua"
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
              <span>Код країни:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="code"
                placeholder="Код країни&hellip;"
                required
                value="{{ $model->code ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Українська назва:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
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
        </fieldset>

        <fieldset class="col-50">
          <legend>Зображення</legend>

          <div class="form-row">
            <label class="caption">
              <span>Завантажити SVG:</span>
            </label>

            @include('admin.layouts.image-upload', [
              'model'  => $model ?? null,
              'field'  => 'img_url',
              'accept' => 'image/svg+xml'
            ])
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
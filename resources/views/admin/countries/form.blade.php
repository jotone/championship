@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.countries.update', $model->id) : route('api.countries.store') }}"
      data-xhr
      data-msg="Country.en"
      method="POST"
    >
      @isset($model)
        @method('PUT')
      @endisset
      @csrf

      <div class="row col-100">
        <fieldset class="col-30">
          <legend>Main information</legend>

          <div class="form-row">
            <label class="caption">
              <span>Country code:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="code"
                data-slug="input.slug"
                placeholder="Country code&hellip;"
                required
                value="{{ $model->code ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Country UA:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="ua"
                placeholder="Country UA&hellip;"
                required
                value="{{ $model->ua ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Country EN:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="en"
                placeholder="Country EN&hellip;" required
                value="{{ $model->en ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Country RU:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="ru"
                placeholder="Country RU&hellip;" required
                value="{{ $model->ru ?? '' }}"
              >
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Image</legend>

          <div class="form-row">
            <label class="caption">
              <span>Upload SVG:</span>
            </label>

            <div class="image-upload-wrap">
              <div class="image-upload-preview">
                @if(isset($model) && !empty($model->img_url))
                  <img src="{{ $model->img_url }}" alt="No image&hellip;">
                @endif
              </div>
              <div class="buttons-wrap">
                <input name="img_url" type="file" style="display: none" accept="image/svg+xml">
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
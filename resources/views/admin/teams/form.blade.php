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
          <legend>Main information</legend>

          <div class="form-row">
            <label class="caption">
              <span>Team UA:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                data-slug="input.en"
                name="ua"
                placeholder="Team UA&hellip;"
                required
                value="{{ $model->ua ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Team EN:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="en"
                placeholder="Team EN&hellip;" required
                value="{{ $model->en ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Country:</span>

              <select name="country_id" class="form-select col-100">
                @foreach($countries as $country)
                  <option value="{{ $country->id }}" @selected(isset($model) && $model->country_id == $country->id)>
                    {{ $country->ua }}
                  </option>
                @endforeach
              </select>
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Image</legend>

          <div class="form-row">
            <label class="caption">
              <span>Upload Image:</span>
            </label>

            <div class="image-upload-wrap">
              <div class="image-upload-preview">
                @if(isset($model) && !empty($model->img_url))
                  <img src="{{ $model->img_url }}" alt="No image&hellip;">
                @endif
              </div>
              <div class="buttons-wrap">
                <input name="img_url" type="file" style="display: none" accept="image/jpeg,image/png,image/svg+xml">
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
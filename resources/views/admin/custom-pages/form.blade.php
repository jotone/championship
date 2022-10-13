@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? 'updateRoute' : 'storeRoute' }}"
      data-xhr
      data-msg="CustomPages.name"
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
              <span>Name:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="name"
                data-slug="input.url"
                placeholder="Name&hellip;"
                required
                value="{{ $model->name ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>URL:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="url"
                placeholder="URL&hellip;"
                required
                value="{{ $model->url ?? '' }}"
                disabled
              >
            </label>
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
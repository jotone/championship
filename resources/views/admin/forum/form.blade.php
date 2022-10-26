@extends('admin.layouts.default')

@section('scripts')
  <script src="/js/ckeditor4/ckeditor.js"></script>
@endsection

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.forum.update', $model->id) : route('api.forum.store') }}"
      data-xhr
      data-msg="Forum Topic.name"
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
              <span>Назва:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="name"
                placeholder="Назва&hellip;"
                required
                value="{{ $model->name ?? '' }}"
              >
            </label>
          </div>

          @if(isset($model) && Auth::user()->role->slug === 'superadmin')
            <div class="form-row">
              <label class="caption">
                <span>URL:</span>

                <input
                  autocomplete="off"
                  class="form-input col-100"
                  name="url"
                  placeholder="URL&hellip;"
                  value="{{ $model->url ?? '' }}"
                >
              </label>
            </div>
          @endif

          <div class="form-row">
            <label class="caption">
              <span>Position:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="position"
                placeholder="Position&hellip;"
                required
                type="number"
                min="0"
                value="{{ $model->position ?? App\Models\ForumTopic::count() }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Description:</span>

              <textarea
                name="description"
                class="form-text col-100"
                placeholder="Description (max 255 chars)&hellip;"
              >{!! $model->description ?? '' !!}</textarea>
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Etc</legend>

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

      <div class="row col-80">
        <fieldset class="col-100">
          <legend>Text</legend>

          <div class="form-row">
            <textarea name="text" class=".cke-init">{{ $model->text ?? '' }}</textarea>
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
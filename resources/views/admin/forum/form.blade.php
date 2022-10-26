@extends('admin.layouts.default')

@section('scripts')
  <script src="/js/ckeditor4/ckeditor.js"></script>
@endsection

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.forum.update', $model->id) : route('api.forum.store') }}"
      data-xhr
      data-msg="Форум.name"
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
              <span>Позиція:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="position"
                placeholder="Позиція&hellip;"
                required
                type="number"
                min="0"
                value="{{ $model->position ?? App\Models\ForumTopic::count() }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Опис:</span>

              <textarea
                name="description"
                class="form-text col-100"
                placeholder="Опис (максимум 255 символів)&hellip;"
              >{!! $model->description ?? '' !!}</textarea>
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Додаткова інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>Завантажити зображення:</span>
            </label>

            @include('admin.layouts.image-upload', ['model' => $model ?? null, 'field' => 'img_url'])
          </div>
        </fieldset>
      </div>

      <div class="row col-80">
        <fieldset class="col-100">
          <legend>Текст</legend>

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
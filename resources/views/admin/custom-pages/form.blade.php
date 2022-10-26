@extends('admin.layouts.default')

@section('scripts')
  <script src="/js/ckeditor4/ckeditor.js"></script>
  <script src="/js/admin/custom-pages-form.js"></script>
@endsection

@php
$disabled = isset($model) && !(Auth::user()->role->slug === 'superadmin' && !$model->editable);
@endphp

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.pages.update', $model->id) : route('api.pages.store') }}"
      data-xhr
      data-msg="Сторінка.name"
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
                @if(!$disabled) data-slug="input.url" @endif
                placeholder="Назва&hellip;"
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
                @if($disabled) disabled @endif
                name="url"
                placeholder="URL&hellip;"
                required
                value="{{ $model->url ?? '' }}"
              >
            </label>
          </div>

          @if(Auth::user()->role->slug === 'superadmin')
            <div class="form-row">
              <label class="caption">
                <span>Аліас:</span>

                <input
                  autocomplete="off"
                  class="form-input col-100"
                  name="slug"
                  placeholder="Аліас&hellip;"
                  required
                  value="{{ $model->slug ?? '' }}"
                >
              </label>
            </div>

            <div class="form-row">
              <label>
                <input name="editable" type="checkbox" @if(isset($model) && $model->editable) checked @endif>
                <span style="margin-left: 10px">Можна редагувати</span>
              </label>
            </div>
          @endif

          <div class="form-row">
            <label>
              <input name="enabled" type="checkbox" @if(isset($model) && $model->enabled) checked @endif>
              <span style="margin-left: 10px">Увімкнено</span>
            </label>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>
            Метадані
          </legend>

          <div class="form-row">
            <label class="caption">
              <span>Заголовок сторінки</span>
              <input
                name="meta_title"
                class="form-input col-100"
                placeholder="Заголовок сторінки&hellip;"
                value="{{ $model->meta_title ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Meta Description</span>
              <textarea
                name="meta_description"
                class="form-text col-100"
                placeholder="Meta Description&hellip;"
              >{{ $model->meta_description ?? '' }}</textarea>
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Meta Keywords</span>
              <textarea
                name="meta_keywords"
                class="form-text col-100"
                placeholder="Meta Keywords&hellip;"
              >{{ $model->meta_keywords ?? '' }}</textarea>
            </label>
          </div>
        </fieldset>
      </div>

      <div class="row">
        <fieldset class="col-80">
          <legend>Вміст</legend>

          <div class="form-row">
            <textarea name="content" class=".cke-init">{{ $model->content ?? '' }}</textarea>
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
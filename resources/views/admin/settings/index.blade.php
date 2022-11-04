@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ route('admin.settings.update') }}"
      data-xhr
      data-msg="Основні налаштування"
      method="POST"
    >
      @method('PATCH')
      @csrf

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>Основна інформація</legend>

          <div class="form-row">
            @include('admin.settings.partials.input-text', ['name' => 'site_title', 'required' => true])
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>{{ $content['fav_icon']->caption }}</legend>

          @include('admin.settings.partials.image-upload', [
            'name' => 'fav_icon',
            'accept' => 'image/svg+xml,image/x-icon,image/vnd.microsoft.icon'
          ])
        </fieldset>
      </div>

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>{{ $content['header_img_url']->caption }}</legend>
          @include('admin.settings.partials.image-upload', [
            'name' => 'header_img_url',
            'accept' => 'image/jpeg,image/png'
          ])
        </fieldset>

        <fieldset class="col-50">
          <legend>{{ $content['logo_img_url']->caption }}</legend>
          @include('admin.settings.partials.image-upload', [
            'name' => 'logo_img_url',
            'accept' => 'image/jpeg,image/png'
          ])
        </fieldset>
      </div>

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>Форматування дат</legend>

          <div class="form-row">
            @include('admin.settings.partials.input-text', ['name' => 'date_format', 'required' => true])
          </div>
          <div class="form-row">
            @include('admin.settings.partials.input-text', ['name' => 'comment_date_format', 'required' => true])
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
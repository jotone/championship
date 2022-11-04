@extends('admin.layouts.default')

@section('styles')
  <link rel="stylesheet" href="/css/admin/color-settings.css">
@endsection

@section('scripts')
  <script src="/js/admin/color-scheme.js"></script>
@endsection

@section('content')
  <div class="form-wrap">
    <form
      action="{{ route('admin.settings.update') }}"
      data-xhr
      data-msg="Налаштування кольорової схеми"
      method="POST"
    >
      @method('PATCH')
      @csrf

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>Верхнє меню</legend>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'top_menu_bg_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'top_menu_font_color', 'required' => true])
          </div>

          <ul
            class="preview-top-menu"
            data-related="top_menu_bg_color"
            data-rule="background-color"
            style="background-color: {{ $content['top_menu_bg_color']->value }}"
          >
            @foreach(explode(' ', preg_replace('/\.\,/', '', $faker->realText(33))) as $word)
              @if(mb_strlen($word) > 2)
                <li>
                  <span
                    data-related="top_menu_font_color"
                    data-rule="color"
                    style="color: {{ $content['top_menu_font_color']->value }}"
                  >
                    {{ mb_ucfirst($word) }}
                  </span>
                </li>
              @endif
            @endforeach
          </ul>
        </fieldset>

        <fieldset class="col-50">
          <legend>Форма входу</legend>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'login_form_bg_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'login_form_font_color', 'required' => true])
          </div>

          <div
            class="preview-form"
            data-related="login_form_bg_color"
            data-rule="background-color"
            style="background-color: {{ $content['login_form_bg_color']->value}}"
          >
            <div>
              <input name="email" type="email" placeholder="Email…">
            </div>
            <div>
              <input name="password" type="password" placeholder="Пароль…">
            </div>
            <div class="form-buttons">
              <a
                href="javascript:void(0)"
                data-related="login_form_font_color"
                data-rule="color"
                style="color: {{ $content['login_form_font_color']->value }}"
              >Забули пароль</a>
            </div>
          </div>
        </fieldset>
      </div>

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>Заголовки та текст</legend>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'title_bg_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'title_font_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'text_main_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'text_secondary_color', 'required' => true])
          </div>

          <div class="preview-page-sample">
            <div
              class="page-heading"
              data-related="title_bg_color"
              data-rule="background-color"
              style="background-color: {{ $content['title_bg_color']->value }}"
            >
              <h1
                data-related="title_font_color"
                data-rule="color"
                style="color: {{ $content['title_font_color']->value }}"
              >{{ $faker->realText(15) }}</h1>
            </div>

            <div
              class="regular-text"
              data-related="text_main_color"
              data-rule="color"
              style="color: {{ $content['text_main_color']->value }}"
            >
              {{ $faker->realText(33) }}
            </div>

            <div
              class="small-text"
              data-related="text_secondary_color"
              data-rule="color"
              style="color: {{ $content['text_secondary_color']->value }}"
            >
              {{ $faker->realText(33) }}
            </div>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Таблиці</legend>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'table_th_bg_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'table_th_font_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'table_td_bg_color', 'required' => true])
          </div>

          <div class="form-row">
            @include('admin.settings.partials.color-picker', ['name' => 'table_td_font_color', 'required' => true])
          </div>

          <table class="preview-table">
            <thead>
            <tr
              data-related="table_th_bg_color"
              data-rule="background-color"
              style="background-color: {{ $content['table_th_bg_color']->value }}"
            >
              <th
                  data-related="table_th_font_color"
                  data-rule="color"
                  style="color: {{ $content['table_th_font_color']->value }}"
                >
                {{ $faker->realText(10) }}
              </th>
              <th
                  data-related="table_th_font_color"
                  data-rule="color"
                  style="color: {{ $content['table_th_font_color']->value }}"
                >
                {{ $faker->realText(10) }}
              </th>
            </tr>
            </thead>

            <tbody>
            @for($i = 0; $i < 2; $i++)
              <tr
                data-related="table_td_bg_color"
                data-rule="background-color"
                style="background-color: {{ $content['table_td_bg_color']->value }}"
              >
                <td>
                  <span
                    data-related="table_td_font_color"
                    data-rule="color"
                    style="color: {{ $content['table_td_font_color']->value }}"
                  >
                    {{ $faker->realText(10) }}
                  </span>
                </td>
                <td>
                  <span
                    data-related="table_td_font_color"
                    data-rule="color"
                    style="color: {{ $content['table_td_font_color']->value }}"
                  >
                    {{ $faker->realText(10) }}
                  </span>
                </td>
              </tr>
            @endfor
            </tbody>
          </table>
        </fieldset>
      </div>

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>{{ $content['primary_btn']->caption }}</legend>

          @include('admin.settings.partials.button-color-picker', ['name' => 'primary_btn', 'required' => true])
        </fieldset>

        <fieldset class="col-50">
          <legend>{{ $content['secondary_btn']->caption }}</legend>

          @include('admin.settings.partials.button-color-picker', ['name' => 'secondary_btn', 'required' => true])
        </fieldset>
      </div>

      <div class="row col-100">
        <fieldset class="col-100">
          <legend>Користувацькі стилі css</legend>

          <div class="form-row">
            <textarea name="custom_styles" class="form-textarea">{{ $custom_styles }}</textarea>
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
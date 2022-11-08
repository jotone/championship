@extends('admin.layouts.default')

@section('styles')
  <link rel="stylesheet" href="/css/admin/color-settings.css">
@endsection

@section('scripts')
  <script src="/js/admin/color-scheme.js"></script>
  <script src="/js/admin/languages-form.js"></script>
@endsection

@section('content')
  <div class="form-wrap">
    <div class="row col-100">
      <form
        action="{{ route('api.languages.upgrade') }}"
        class="col-50"
        data-xhr
        data-msg="Налаштування мови"
        name="language-settings"
        method="POST"
      >
        @method('PATCH')
        @csrf

        <fieldset class="col-100">
          <legend>Основна інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>{{ $content['admin_lang']->caption }}:</span>

              <select class="form-select col-50" name="admin_lang">
                @foreach($content['lang_list']->converted_value as $item)
                  <option value="{{ $item }}" @if($item == $content['admin_lang']->value) selected @endif>
                    {{ mb_ucfirst($langs[$item]) }}
                  </option>
                @endforeach
              </select>
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>{{ $content['main_lang']->caption }}:</span>

              <select class="form-select col-50" name="main_lang">
                @foreach($content['lang_list']->converted_value as $item)
                  <option value="{{ $item }}" @if($item == $content['main_lang']->value) selected @endif>
                    {{ mb_ucfirst($langs[$item]) }}
                  </option>
                @endforeach
              </select>
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>{{ $content['lang_list']->caption }}:</span>
            </label>

            <ul class="language-list-wrap sortable-list-wrap">
              @foreach($installed as $item => $data)
                <li @if(!in_array($item, $content['lang_list']->converted_value)) class="disabled" @endif>
                  <div class="language-list-move move-handle">
                    <i class="fas fa-ellipsis-v"></i>
                    <i class="fas fa-ellipsis-v"></i>
                  </div>
                  <div class="language-list-name">
                    <span>{{ mb_ucfirst($langs[$item]) }}</span>
                      <input
                        name="lang_list[]"
                        type="hidden"
                        value="{{ $item }}"
                        @if(!in_array($item, $content['lang_list']->converted_value)) disabled @endif
                      >
                  </div>
                  @if(in_array($item, $content['lang_list']->converted_value))
                    <div class="language-list-remove">
                      <i class="fas fa-times remove"></i>
                    </div>
                  @else
                    <div class="language-list-add">
                      <i class="fas fa-plus edit"></i>
                    </div>
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        </fieldset>
      </form>

      <form
        action="{{ route('api.languages.store') }}"
        class="col-50"
        data-xhr
        data-msg="Налаштування мови"
        method="POST"
        name="language-store"
      >
        @csrf
        <fieldset class="col-100">
          <legend>Додати мову</legend>

          <div class="form-row">
            <label class="caption">
              <span>Встановити мову</span>

              <select name="lang" class="form-select col-50">
                <option disabled selected>Виберіть мову</option>
                @foreach($langs as $key => $lang)
                  @if(!in_array($key, $content['lang_list']->converted_value))
                    <option value="{{ $key }}">{{ mb_ucfirst($lang) }}</option>
                  @endif
                @endforeach
              </select>
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Список встановлених мов:</span>
            </label>

            <ul class="language-list-wrap">
              @foreach($installed as $item => $data)
                <li>
                  <div class="language-list-name">
                    <span>{{ mb_ucfirst($langs[$item]) }}</span>
                    <input name="remove" type="hidden" value="{{ route('api.languages.destroy', $item) }}">
                  </div>
                  @if($item != 'en')
                    <div class="language-list-remove">
                      <i class="fas fa-times remove"></i>
                    </div>
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        </fieldset>
      </form>
    </div>

    <form
      action="{{ route('api.languages.update', 0) }}"
      data-xhr
      data-msg="Налаштування мови"
      method="POST"
    >
      <div class="row col-100">
        <fieldset class="col-100">
          <legend>Список перекладів</legend>

          <div class="translations-form" data-routes="{{ json_encode($routes) }}">
            <ul>
              @foreach($installed as $item => $data)
                <li data-show="{{ $item }}" @if($loop->first) class="active" @endif>
                  <a href="{{ route('api.languages.show', $item) }}">
                    {{ mb_ucfirst($langs[$item]) }}
                  </a>
                </li>
              @endforeach
            </ul>

            @foreach($installed as $item => $data)
              <div class="button-color-picker {{ $loop->first ? 'active' : '' }}" data-type="{{ $item }}">
                <div class="translation-list-wrap">
                  <ul class="file-list-wrap">
                    @foreach($data as $file)
                      <li @if($loop->first) class="active" @endif>
                        <a href="#" data-file="{{ $file }}">
                          {{ ucfirst($file) }}
                        </a>
                      </li>
                    @endforeach
                  </ul>

                  <div class="translation-list col-100"></div>
                </div>
              </div>
            @endforeach
          </div>
        </fieldset>
      </div>
    </form>
  </div>
@endsection
@extends('admin.layouts.default')

@section('styles')
  <link rel="stylesheet" href="/css/admin/color-settings.css">
@endsection

@section('scripts')
  <script src="/js/admin/languages-form.js"></script>
  <script src="/js/admin/color-scheme.js"></script>
@endsection

@section('content')
  <div class="form-wrap">
    <form
      action="#"
      data-xhr
      data-msg="Налаштування мови"
      method="POST"
    >
      @method('PUT')
      @csrf

      <div class="row col-100">
        <fieldset class="col-50">
          <legend>Основна інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>{{ $content['admin_lang']->caption }}:</span>

              <select class="form-select col-50" name="admin_lang">
                @foreach($content['lang_list']->converted_value as $item)
                  <option value="{{ $item }}" @if($item == $content['lang_list']->value) selected @endif>
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
              @foreach($content['lang_list']->converted_value as $item)
              <li>
                <div class="language-list-move move-handle">
                  <i class="fas fa-ellipsis-v"></i>
                  <i class="fas fa-ellipsis-v"></i>
                </div>
                <div class="language-list-name">
                  <span>{{ mb_ucfirst($langs[$item]) }}</span>
                  <input name="lang_list[]" type="hidden" value="{{ $item }}">
                </div>
                <div class="language-list-remove">
                  <i class="fas fa-times remove"></i>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </fieldset>

        <fieldset class="col-50">
          <legend>Додати мову</legend>

          <div class="form-row">
            <label class="caption">
              <span>Встановити мову</span>

              <select name="addLang" class="form-select col-50">
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
              @foreach($installed as $item)
                <li>
                  <div class="language-list-name">
                    <span>{{ mb_ucfirst($langs[$item]) }}</span>
                    <input name="lang_list[]" type="hidden" value="{{ $item }}">
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
      </div>
    </form>

    <form
      action="#"
      data-xhr
      data-msg="Налаштування мови"
      method="POST"
    >
      <div class="row col-100">
        <fieldset class="col-100">
          <legend>Список перекладів</legend>

          <div class="button-color-picker-wrap">
            <ul>
              @foreach($installed as $item)
                <li data-show="{{ $item }}" @if($loop->first) class="active" @endif>
                  <span>{{ mb_ucfirst($langs[$item]) }}</span>
                </li>
              @endforeach
            </ul>

            @foreach($installed as $item)
              <div class="button-color-picker {{ $loop->first ? 'active' : '' }}" data-type="{{ $item }}">
                {{ $item }}
              </div>
            @endforeach
          </div>
        </fieldset>
      </div>
    </form>
  </div>
@endsection
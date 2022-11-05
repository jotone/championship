@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ route('admin.settings.languages.update') }}"
      data-xhr
      data-msg="Налаштування мови"
      method="POST"
    >
      @method('PATCH')
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

            <ul>

            </ul>
          </div>
        </fieldset>
      </div>
    </form>
  </div>
@endsection
<label class="caption">
  <span>{{ $content[$name]->caption }}:</span>

  <input
    autocomplete="off"
    class="form-input col-100"
    name="{{ $name }}"
    placeholder="{{ $content[$name]->caption }}&hellip;"
    @isset($required) required @endisset
    value="{{ $content[$name]->value ?? '' }}"
  >
</label>
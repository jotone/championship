<label class="caption">
  <span>{{ $content[$name]->caption }}:</span>

  <input
    autocomplete="off"
    class="form-input col-100 jscolor"
    data-jscolor="{format:&quot;hex&quot;}"
    name="{{ $name }}"
    @isset($required) required @endisset
    value="{{ $content[$name]->value ?? '' }}"
  >
</label>
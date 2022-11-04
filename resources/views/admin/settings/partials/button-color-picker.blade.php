<div class="button-color-picker-wrap">
  <ul>
    <li class="active" title="normal" data-show="normal">
      <span>Звичайний</span>
    </li>
    <li title=":hover" data-show="hover">
      <span>Наведення</span>
    </li>
  </ul>

  @foreach($content[$name]->converted_value as $type => $data)
    <div class="button-color-picker {{ $loop->first ? 'active' : '' }}" data-type="{{ $type }}">

      @foreach($data as $field => $color)
        <div class="button-color-picker-row">
          <label class="caption">
            <span>{{ $field }}:</span>

            <input
              autocomplete="off"
              class="form-input col-100 jscolor"
              data-jscolor="{format:&quot;hex&quot;}"
              name="{{ $name }}[{{ $type }}][{{ $field }}]"
              @isset($required) required @endisset
              value="{{ $color }}"
            >
          </label>
        </div>
      @endforeach
    </div>
  @endforeach
</div>
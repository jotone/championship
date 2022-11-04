<div class="image-upload-wrap">
  <div class="image-upload-preview">
    @if(!empty($content[$name]->value) && file_exists(public_path($content[$name]->value)))
      <img src="{{ $content[$name]->value }}" alt="Зображення відсутнє&hellip;">
    @endif
  </div>
  <div class="buttons-wrap">
    <input
      name="{{ $name }}"
      type="file"
      style="display: none"
      accept="{{ $accept ?? 'image/jpeg,image/png,image/svg+xml' }}"
    >
    <button name="upload" class="btn" type="button">
      <span>Завантажити</span>
    </button>
  </div>
</div>
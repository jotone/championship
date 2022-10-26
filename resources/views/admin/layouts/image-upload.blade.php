<div class="image-upload-wrap">
  <div class="image-upload-preview">
    @if(isset($model) && !empty($model->$field))
      <img src="{{ $model->$field }}" alt="Зображення відсутнє&hellip;">
    @endif
  </div>
  <div class="buttons-wrap">
    <input
      name="{{ $field }}"
      type="file"
      style="display: none"
      accept="{{ $accept ?? 'image/jpeg,image/png,image/svg+xml' }}"
    >
    <button name="upload" class="btn" type="button">
      <span>Завантажити</span>
    </button>
    <button name="clear" class="btn cancel" type="button">
      <span>Очистити</span>
    </button>
  </div>
</div>
<form
  action="{{ isset($model) ? route('api.competitions.update', $model->id) : route('api.competitions.store') }}"
  data-xhr
  data-redirect="{{ route('admin.competitions.edit', 0) }}"
  data-msg="Competition.name"
  style="display: {{ $tab == 'competition' ? 'block' : 'none' }}"
  method="POST"
>
  @isset($model)
    @method('PUT')
  @endisset
  @csrf

  <div class="row col-100">
    <fieldset class="col-30">
      <legend>Main information</legend>

      <div class="form-row">
        <label class="caption">
          <span>Name:</span>

          <input
            autocomplete="off"
            class="form-input col-100"
            name="name"
            data-slug="input.slug"
            placeholder="Name&hellip;"
            required
            value="{{ $model->name ?? '' }}"
          >
        </label>
      </div>

      <div class="form-row">
        <label class="caption">
          <span>Slug:</span>

          <input
            autocomplete="off"
            class="form-input col-100"
            name="slug"
            placeholder="Slug&hellip;"
            required
            value="{{ $model->slug ?? '' }}"
          >
        </label>
      </div>

      <div class="form-row">
        <label class="caption">
          <span>Start date:</span>

          <input
            autocomplete="off"
            class="form-input col-100 datepicker"
            name="start_at"
            placeholder="Start date&hellip;"
            required
            value="{{ isset($model) ? $model->start_at->format('d.m.Y') : '' }}"
          >
        </label>
      </div>

      <div class="form-row">
        <label class="caption">
          <span>Finish date:</span>

          <input
            autocomplete="off"
            class="form-input col-100 datepicker"
            name="finish_at"
            placeholder="Finish date&hellip;"
            value="{{ isset($model) ? $model->finish_at->format('d.m.Y') : '' }}"
          >
        </label>
      </div>

      <div class="form-row">
        <label class="caption">
          <span>Number of Groups:</span>

          <input
            autocomplete="off"
            class="form-input col-100"
            name="groups_number"
            placeholder="Number of Groups&hellip;"
            required
            type="number"
            min="1"
            max="25"
            value="{{ $model->groups_number ?? '1' }}"
          >
        </label>
      </div>
    </fieldset>

    <fieldset class="col-50">
      <legend>Misc information</legend>

      <div class="form-row">

        <label class="caption">
          <span>Upload Image:</span>
        </label>

        <div class="image-upload-wrap">
          <div class="image-upload-preview">
            @if(isset($model) && !empty($model->img_url))
              <img src="{{ $model->img_url }}" alt="No image&hellip;">
            @endif
          </div>
          <div class="buttons-wrap">
            <input name="img_url" type="file" style="display: none" accept="image/jpeg,image/png,image/svg+xml">
            <button name="upload" class="btn" type="button">
              <span>Upload</span>
            </button>
            <button name="clear" class="btn cancel" type="button">
              <span>Clear</span>
            </button>
          </div>
        </div>
      </div>

      <div class="form-row">
        <label class="caption">
          <span>Background color:</span>

          <input
            autocomplete="off"
            class="form-input col-100"
            name="bg_color"
            data-jscolor="{format:&quot;hex&quot;}"
            placeholder="Background color&hellip;"
            required
            value="{{ $model->bg_color ?? '#ffffff' }}"
          >
        </label>
      </div>

      <div class="form-row">
        <label class="caption">
          <span>Text color:</span>

          <input
            autocomplete="off"
            class="form-input col-100"
            name="text_color"
            data-jscolor="{format:&quot;hex&quot;}"
            placeholder="Text color&hellip;"
            required
            value="{{ $model->text_color ?? '#0d0d0d' }}"
          >
        </label>
      </div>
    </fieldset>
  </div>

  <div class="row">
    <button type="submit" class="btn success">
      Save
    </button>
  </div>
</form>
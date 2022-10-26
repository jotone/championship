<div class="popup-wrap" id="add-group-game">
  <div class="popup-close">
    <i class="fas fa-times"></i>
  </div>
  <div class="popup-title">Add Game. Select teams</div>

  <div class="popup-content-wrap">
    <form
      class="form-wrap"
      action="{{ route('api.competition-group-games.store') }}"
      method="POST"
    >
      @csrf

      <input name="group_id" type="hidden" value="">
      <input name="entity" type="hidden" value="">
      <div class="row col-100">
        <div class="form-row col-100">
          <label class="caption">
            <span>Господарі:</span>

            <select name="host_team" class="form-select col-100">
            </select>
          </label>
        </div>

        <div class="form-row col-100">
          <label class="caption">
            <span>Гості:</span>

            <select name="guest_team" class="form-select col-100">
            </select>
          </label>
        </div>
      </div>

      <div class="row" style="margin-top: 15px">
        <button type="submit" class="btn success">
          Зберегти
        </button>
      </div>
    </form>
  </div>
</div>
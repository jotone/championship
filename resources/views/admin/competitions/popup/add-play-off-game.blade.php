<div class="popup-wrap" id="add-play-off-game" style="max-width: 640px">
  <div class="popup-close">
    <i class="fas fa-times"></i>
  </div>
  <div class="popup-title">Додавання Команд. Виберіть команди</div>

  <div class="popup-content-wrap">
    <form
      class="form-wrap"
      action="{{ route('api.competition-group-games.create') }}"
      method="POST"
    >
      @csrf

      <input name="group_id" type="hidden" value="">
      <input name="entity" type="hidden" value="">
      <div class="stage-teams-list col-100">
        <div class="form-row col-100">
          <label class="caption">
            <select name="team" class="form-select col-100">${this.teamsOptionsList()}</select>
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
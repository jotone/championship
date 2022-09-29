<div class="popup-wrap" id="add-team">
  <div class="popup-close">
    <i class="fas fa-times"></i>
  </div>
  <div class="popup-title">Select team or country</div>

  <div class="popup-content-wrap">
    <form
      class="form-wrap"
      action="{{ route('api.competition-group-teams.store') }}"
      method="POST"
    >
      @csrf

      <input name="group_id" type="hidden" value="">
      <div class="row col-100">
        <div class="form-row col-100">
          <label class="caption">
            <span>Type:</span>

            <select name="entity" class="form-select col-100">
              <option value="App\Models\Team" data-url="{{ route('api.teams.index') }}?order[by]=ua">
                Team
              </option>
              <option value="App\Models\Country" data-url="{{ route('api.countries.index') }}?order[by]=ua">
                Country
              </option>
            </select>
          </label>
        </div>
      </div>

      <div
        class="team-selector"
        data-name="entity_id"
        data-url="{{ route('api.teams.index') }}?order[by]=ua"
      ></div>

      <div class="row" style="margin-top: 15px">
        <button type="submit" class="btn success">
          Save
        </button>
      </div>
    </form>

  </div>
</div>
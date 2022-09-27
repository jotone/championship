@extends('admin.layouts.default')

@section('content')
  @isset($model)
    <ul class="bookmark-wrap">
      <li @if($tab == 'competition') class="active" @endif data-show="competition">
        <a href="?tab=competition">Competition</a>
      </li>
      <li @if($tab == 'groups') class="active" @endif data-show="groups">
        <a href="?tab=groups">Groups</a>
      </li>
      @if($model->groups_number > 1)
        <li @if($tab == 'play-offs') class="active" @endif data-show="play-offs">
          <a href="?tab=play-offs">Play-offs</a>
        </li>
      @endif
    </ul>
  @endisset

  <div class="form-wrap">
    @include('admin.competitions.partials.main-form')

    @isset($model)
      <div
        class="col-100"
        id="groupsTable"
        data-routes="{{ json_encode($routes) }}"
        style="display: {{ $tab == 'groups' ? 'block' : 'none' }}"
      ></div>
      <div class="row col-100" style="display: {{ $tab == 'play-offs' ? 'block' : 'none' }}">3</div>
    @endisset
  </div>

@endsection

@section('popup')
  <div class="popup-wrap" id="add-group">
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
        <div class="row col-100">
          <div class="form-row col-100">
            <label class="caption">
              <span>Host team:</span>

              <select name="host_team" class="form-select col-100">
              </select>
            </label>
          </div>

          <div class="form-row col-100">
            <label class="caption">
              <span>Guest team:</span>

              <select name="guest_team" class="form-select col-100">
              </select>
            </label>
          </div>
        </div>

        <div class="row" style="margin-top: 15px">
          <button type="submit" class="btn success">
            Save
          </button>
        </div>
      </form>
    </div>
  </div>


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
@endsection

@section('scripts')
  @vite([
    'node_modules/air-datepicker/air-datepicker.css',
    'resources/js/admin/competition-form/app.js'
  ])
@endsection
@extends('main.layouts.default')

@section('content')
  @foreach($competition->groups as $group)

    <table class="content-table">
      <thead>
      <tr>
        <th colspan="4">
          <span>{{ $group->name }}</span>
        </th>
      </tr>
      </thead>
      <tbody>
      @if($group->stage > 0)
        @if($group->games_number > 0)
          @for($i = 0; $i < $group->games_number; $i++)
            <tr>
              <td colspan="2">
                <select class="team-selector" name="group[{{ $group->id }}][{{ $i }}][0]" required>
                  <option value="0">Виберіть команду</option>
                  @foreach($teams as $team_entity)
                    <option value="{{ $team_entity->entity_id }}">
                      {{ $team_entity->team->ua }}
                    </option>
                  @endforeach
                </select>
              </td>
              <td colspan="2">
                <select class="team-selector" name="group[{{ $group->id }}][{{ $i }}][1]" required>
                  <option value="0">Виберіть команду</option>
                  @foreach($teams as $team_entity)
                    <option value="{{ $team_entity->entity_id }}">
                      {{ $team_entity->team->ua }}
                    </option>
                  @endforeach
                </select>
              </td>
            </tr>
          @endfor
        @else
          <tr>
            <td colspan="4" style="text-align: center">
              <select class="team-selector">
                <option value="0">Виберіть команду</option>
                @foreach($teams as $team_entity)
                  <option value="{{ $team_entity->entity_id }}">
                    {{ $team_entity->team->ua }}
                  </option>
                @endforeach
              </select>
            </td>
          </tr>
        @endif
      @else
        @foreach($group->games as $game)
          <tr>
            <td><span>{{ $game->hostTeam->ua }}</span></td>
            <td>
              <input
                class="team-score-input "
                min="0"
                name="game[{{ $game->id }}][{{ $game->host_team }}]"
                required
                type="number"
              >
            </td>
            <td>
              <input
                class="team-score-input "
                min="0"
                name="game[{{ $game->id }}][{{ $game->guest_team }}]"
                required
                type="number"
              >
            </td>
            <td><span>{{ $game->guestTeam->ua }}</span></td>
          </tr>
        @endforeach
      @endif
      </tbody>
    </table>
  @endforeach

@endsection
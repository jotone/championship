@extends('main.layouts.default')

@section('scripts')
  @vite(['resources/js/main/user-form.js'])
@endsection

@section('content')
  <form
    action="{{ route('user.form.store', $competition->id) }}"
    name="userForm"
    method="post"
    data-teams="{{ base64_encode(json_encode($teams->pluck('ua', 'id')->toArray())) }}"
  >
    @csrf

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

        @if ($group->stage > 0)
          @if($group->games_number > 0)
            @for($i = 0; $i < $group->games_number; $i++)
              <tr>
                <td colspan="2" data-i="{{ ($i * 2) }}">
                  <select class="team-selector" name="group[{{ $group->id }}][]" required></select>
                </td>
                <td colspan="2" data-i="{{ ($i * 2) + 1}}">
                  <select class="team-selector" name="group[{{ $group->id }}][]" required></select>
                </td>
              </tr>
            @endfor
          @else
            <tr>
              <td colspan="4" style="text-align: center" data-i="0">
                <select class="team-selector" name="group[{{ $group->id }}][]" required></select>
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

    <div class="content-block-wrap flex-c">
      <button type="submit" class="btn regular">
        <span>Отправить анкету</span>
      </button>
    </div>
  </form>
@endsection
@extends('main.layouts.simple')

@section('styles')
  <link rel="stylesheet" href="/css/summary.css">
@endsection

@section('content')
  @dd($groups, $users);
  <div class="summary-wrapper">
    <div class="games-list">
      <ul>
{{--        @foreach($competition->groups as $group)--}}
{{--          <li>--}}
{{--            <div class="group-name">--}}
{{--              {{ $group->name }}--}}
{{--            </div>--}}

{{--            <ul>--}}
{{--              @foreach($group->games as $game)--}}
{{--                @if($group->stage > 0)--}}
{{--                  @foreach($game->score as $team_id)--}}
{{--                    <li class="game-teams-list">--}}
{{--                      {{ $teams[$team_id]->ua }}--}}
{{--                    </li>--}}
{{--                  @endforeach--}}
{{--                @else--}}
{{--                  <li class="group-games-list">--}}
{{--                    <div class="game-team-names">--}}
{{--                      <span>{{ $teams[$game->host_team]->ua }}</span>--}}
{{--                      <span>:</span>--}}
{{--                      <span>{{ $teams[$game->guest_team]->ua }}</span>--}}
{{--                    </div>--}}
{{--                    <div class="game-score">--}}
{{--                      @if($game->accept)--}}
{{--                        <span>{{ $game->score[$game->host_team] }}</span>--}}
{{--                        <span>-</span>--}}
{{--                        <span>{{ $game->score[$game->guest_team] }}</span>--}}
{{--                      @endif--}}
{{--                    </div>--}}
{{--                  </li>--}}
{{--                @endif--}}
{{--              @endforeach--}}
{{--            </ul>--}}
{{--          </li>--}}
{{--        @endforeach--}}
      </ul>
    </div>

    <div class="user-list-wrap">
      <table class="user-list">
        <thead>
        <tr>
{{--          @foreach($forms as $form)--}}
{{--            <th class="rotate">--}}
{{--              <div><span>{{ $form->user->name }}</span></div>--}}
{{--            </th>--}}
{{--          @endforeach--}}
        </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
@endsection
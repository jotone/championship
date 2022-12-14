<aside class="real-score-wrap">
  @if($competition)
    <table class="content-table real-score">
      <thead>
      <tr>
        <th colspan="4">Дійсний рахунок</th>
      </tr>
      </thead>
      <tbody>
      @foreach($competition->groups()->where('stage', 0)->get() as $group)
        @foreach($group->games as $game)
          <tr @if($loop->last) class="group-finish" @endif>
            <td>
              <span>{{ $game->hostTeam->ua }}</span>
            </td>
            <td>
              <span>{{ !empty($game->accept) ? $game->score[$game->host_team] : '' }}</span>
            </td>
            <td>
              <span>{{ !empty($game->accept) ? $game->score[$game->guest_team] : '' }}</span>
            </td>
            <td>
              <span>{{ $game->guestTeam->ua }}</span>
            </td>
          </tr>
        @endforeach
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td colspan="4">
          <span>&#10048;</span>
        </td>
      </tr>
      </tfoot>
    </table>

    @php
      $groups = $competition->groups()->where('stage', '>', 0)->get()
    @endphp

    @foreach($groups as $i => $group)
      <table class="content-table real-score" data-uuid="{{ md5($group->id) }}">
        <thead>
        <tr>
          <th colspan="2">{{ $group->name }} @if($group->games_number > 1) фіналісти @endif </th>
        </tr>
        </thead>
        <tbody>
        @if($group->games->count())
          @if($loop->last)
              <tr>
                @isset($group->games[0]->score[0])
                <td colspan="2" style="text-align: center" data-uuid="{{ md5($group->games[0]->score[0]) }}">
                  <span style="font-size: 1.5em;">{{ $teams[$group->games[0]->score[0]]->ua }}</span>
                </td>
                @endisset
              </tr>
          @else
            @foreach($group->games[0]->score as $j => $team_id)
              @if($j % 2 == 0)
                <tr>
              @endif

                  <td style="width: 50%" data-uuid="{{ md5($team_id) }}">
                    <span>{{ $teams[$team_id]->ua }}</span>
                  </td>

              @if($j % 2 == 1 || $loop->last)

                @if($loop->last && count($group->games[0]->score) % 2 == 1)
                  <td></td>
                @endif

                </tr>
              @endif
            @endforeach
          @endif
        @endif
        </tbody>
        <tfoot>
        <tr>
          <td colspan="4">
            <span>&#10048;</span>
          </td>
        </tr>
        </tfoot>
      </table>
    @endforeach
  @endif
</aside>
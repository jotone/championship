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
          <tr>
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
          <th colspan="2">
            {{ $group->name }} @if($group->games_number > 1) фіналісти @endif</th>
        </tr>
        </thead>
        <tbody>
          @if($loop->last)
            @if ($groups[$i - 1]->games->count())
              <tr>
                @php
                $game = $groups[$i - 1]->games[0];
                $winner = $game->score[$game->host_team] > $game->score[$game->guest_team] ? $game->hostTeam : $game->guestTeam;
                @endphp
                <td colspan="2" style="text-align: center" data-uuid="{{ md5($winner->id) }}">
                  <span style="font-size: 1.5em;">{{ $winner->ua }}</span>
                </td>
              </tr>
            @endif
          @else
            @foreach($group->games as $game)
              <tr>
                <td style="width: 50%" data-uuid="{{ md5($game->host_team) }}">
                  <span>{{ $game->hostTeam->ua }}</span>
                </td>
                <td style="width: 50%" data-uuid="{{ md5($game->guest_team) }}">
                  <span>{{ $game->guestTeam->ua }}</span>
                </td>
              </tr>
            @endforeach
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
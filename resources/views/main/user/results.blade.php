@extends('main.layouts.default')

@section('scripts')
  <script src="/js/results.js"></script>
@endsection

@section('content')
  @if($competition)
    <table class="content-table">
      <thead>
      <tr>
        <th colspan="5">Анкета учасника {{ $user->name }}</th>
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
              <span>
                {{ $bets['group'][$group->id][$game->id][$game->host_team] ?? null }}
              </span>
            </td>
            <td>
              <span>{{ $bets['group'][$group->id][$game->id][$game->guest_team] ?? null }}</span>
            </td>
            <td>
              <span>{{ $game->guestTeam->ua }}</span>
            </td>
            <td>
            </td>
          </tr>
        @endforeach
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td colspan="5">
          <span>Кількість очок за групу</span>

          <div class="total-points">
            <span></span>
          </div>
        </td>
      </tr>
      </tfoot>
    </table>

    @php
      $groups = $competition->groups()->where('stage', '>', 0)->get()
    @endphp
    @foreach($groups as $i => $group)
      <table class="content-table" data-uuid="{{ md5($group->id) }}">
        <thead>
        <tr>
          <th colspan="2">
            {{ $group->name }}
            @if($group->games_number > 1)
              фіналісти
            @endif
          </th>
        </tr>
        </thead>
        <tbody>
        @if($loop->last)
          <tr>
            <td
              colspan="2"
              style="text-align: center"
              data-uuid="{{ md5($bets['playOff'][$group->id][0]) }}"
            >
              <span style="font-size: 1.5em;">
                @isset($bets['playOff'][$group->id][0])
                  {{ $teams[$bets['playOff'][$group->id][0]]->ua }}
                @endif
              </span>
            </td>
          </tr>
        @else
          @for($i = 0; $i < $group->games_number; $i++)
            <tr>
              <td
                style="width: 50%"
                @isset($bets['playOff'][$group->id][$i * 2]) data-uuid="{{ md5($bets['playOff'][$group->id][$i * 2]) }}" @endisset
              >
                <span>
                  @isset($bets['playOff'][$group->id][$i * 2])
                    {{ $teams[$bets['playOff'][$group->id][$i * 2]]->ua }}
                  @endisset
                </span>
              </td>
              <td
                style="width: 50%"
                @isset($bets['playOff'][$group->id][$i * 2]) data-uuid="{{ md5($bets['playOff'][$group->id][($i * 2) + 1]) }}" @endisset
              >
                <span>
                  @isset($bets['playOff'][$group->id][($i * 2) + 1])
                    {{ $teams[$bets['playOff'][$group->id][($i * 2) + 1]]->ua }}
                  @endisset
                </span>
              </td>
            </tr>
          @endfor
        @endif
        </tbody>
        <tfoot>
        <tr>
          <td colspan="2">
            <span>
              Кількість очок за
              @if($group->games_number > 1)
                 {{ $group->name }} фіналу
              @elseif ($group->games_number == 1)
                {{ $group->name }}
              @else
                Чемпіона
              @endif

              <br>(команди - <em class="commands">0</em> @if($group->games_number > 0) / бонус - <em class="bonus">0</em> @endif)
            </span>

            <div class="total-points">
              <span></span>
            </div>
          </td>
        </tr>
        </tfoot>
      </table>
    @endforeach
  @endif
@endsection
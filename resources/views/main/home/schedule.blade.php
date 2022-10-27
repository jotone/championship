@extends('main.layouts.default')

@section('content')
  <table class="content-table">
    <thead>
    <tr>
      <th colspan="4"><span>Групи</span></th>
    </tr>
    </thead>

    <tbody>
    @foreach($competition->groups as $competition_group)
      @if(!$competition_group->stage && $competition_group->games->count())
        @foreach($competition_group->games as $game)
          <tr @if($loop->last) class="group-finish" @endif>
            @if ($loop->first)
            <td class="rotate" rowspan="{{ $competition_group->games->count() }}">
              <span>{{ $competition_group->name }}</span>
            </td>
            @endif
            <td style="width: 30%">
              <span>{{ $game->hostTeam->ua }}</span>
            </td>
            <td style="width: 30%">
              <span>{{ $game->guestTeam->ua }}</span>
            </td>
            <td style="width: 40%">
              <span>{{ !empty($game->start_at) ? $game->start_at->translatedFormat('j/M/Y H:i') : '' }}</span>
            </td>
          </tr>
        @endforeach
      @endif
    @endforeach
    </tbody>
  </table>
@endsection
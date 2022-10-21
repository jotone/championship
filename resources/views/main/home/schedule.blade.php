@extends('main.layouts.default')

@section('content')
  @foreach($competition->groups as $competition_group)
    @if($competition_group->games->count())
      <table class="content-table">
        <thead>
        <tr>
          <th colspan="3"><span>{{ $competition_group->name }}</span></th>
        </tr>
        </thead>

        <tbody>
        @foreach($competition_group->games as $game)
          <tr>
            <td>
              <span>{{ $game->hostTeam->ua }}</span>
            </td>
            <td>
              <span>{{ $game->guestTeam->ua }}</span>
            </td>
            <td>
              <span>{{ !empty($game->start_at) ? $game->start_at->translatedFormat('j/M/Y H:i') : '' }}</span>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    @endif
  @endforeach
@endsection
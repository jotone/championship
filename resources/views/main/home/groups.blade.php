@extends('main.layouts.default')

@section('content')
  @foreach($competition->groups as $competition_group)
    @if(!$competition_group->stage && $competition_group->games->count())
      <table class="content-table">
        <thead>
        <tr>
          <th style="width: 200px"><span>{{ $competition_group->name }}</span></th>
          <th title="Кількість ігор"><span>І</span></th>
          <th title="Кількість виграшів"><span>В</span></th>
          <th title="Кількість нічиїх"><span>Н</span></th>
          <th title="Кількість програшів"><span>П</span></th>
          <th title="М'ячі: забиті-пропущені"><span>М</span></th>
          <th title="Рахунок"><span>Р</span></th>
        </tr>
        </thead>

        <tbody>
        @foreach($competition_group->teams as $team)
          <tr>
            <td>{{ $teams[$team->entity_id]->ua }}</td>
            <td><span>{{ $team->games }}</span></td>
            <td><span>{{ $team->wins }}</span></td>
            <td><span>{{ $team->draws }}</span></td>
            <td><span>{{ $team->loses }}</span></td>
            <td><span>{{ $team->balls }}</span></td>
            <td><span>{{ $team->score }}</span></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    @endif
  @endforeach
@endsection
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

      @if($model->groups_number > 1)
        <div
          class="col-100"
          id="playOffTable"
          data-routes="{{ json_encode($routes) }}"
          style="display: {{ $tab == 'play-offs' ? 'block' : 'none' }}"
        ></div>
      @endif
    @endisset
  </div>

@endsection

@section('popup')
  @include('admin.competitions.popup.add-group')

  @include('admin.competitions.popup.add-team')
@endsection

@section('scripts')
  @vite([
    'node_modules/air-datepicker/air-datepicker.css',
    'resources/js/admin/competition-form/app.js'
  ])
@endsection
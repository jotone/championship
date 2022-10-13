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
      @if($tab == 'groups')
        <div class="col-100" id="groupsTable" data-routes="{{ json_encode($routes) }}"></div>
      @endif

      @if($model->groups_number > 1 && $tab == 'play-offs')
        <div class="col-100" id="playOffTable" data-routes="{{ json_encode($routes) }}" data-id="{{ $model->id }}"></div>
      @endif
    @endisset
  </div>

@endsection

@section('popup')
  @include('admin.competitions.popup.add-group-game')

  @include('admin.competitions.popup.add-team')
@endsection

@section('scripts')
  <link rel="stylesheet" href="/css/admin/competition.css">
  <link rel="stylesheet" href="/css/air-datepicker.css">
  <script src="/js/admin/competition-form.js"></script>
@endsection
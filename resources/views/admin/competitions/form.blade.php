@extends('admin.layouts.default')

@section('content')
  @isset($model)
    <ul class="bookmark-wrap">
      <li class="active" data-show="competition">Competition</li>
      <li data-show="groups">Groups</li>
      @if($model->groups_number > 1)
        <li data-show="play-offs">Play-offs</li>
      @endif
    </ul>
  @endisset

  <div class="form-wrap">
    @include('admin.competitions.partials.main-form')

    @isset($model)
      <div class="col-100" data-show="groups">
        @foreach($model->groups as $group)
          <table class="competition-table">
            <thead>
              <tr>
                <th>
                  Group {{ $group->name }}
                </th>
                <th class="border">
                  <span class="add fas fa-plus-circle" title="Add Team"></span>
                </th>
                <th>Games</th>
                <th>Wins</th>
                <th>Draws</th>
                <th>Loses</th>
                <th>Balls</th>
                <th>Score</th>
              </tr>
            </thead>
            <tbody>
            <tr data-role="add-team">
              <td colspan="8">
                <span class="add fas fa-plus-circle" title="Add Team"></span>
              </td>
            </tr>
            </tbody>
          </table>
        @endforeach
      </div>
      <div class="row col-100" data-show="play-offs">3</div>
    @endisset
  </div>

  <div class="popup-wrap" id="append-team">
    <div class="popup-close">
      <i class="fas fa-times-circle"></i>
    </div>

    <div class="popup-content-wrap">

    </div>
  </div>
@endsection

@section('popup')
  <div class="popup-wrap" id="append-team">
    <div class="popup-close">
      <i class="fas fa-times-circle"></i>
    </div>

    <div class="popup-content-wrap">

    </div>
  </div>
@endsection

@section('scripts')
  @vite([
    'node_modules/air-datepicker/air-datepicker.css',
    'resources/js/admin/competition-form/app.js'
  ])
@endsection
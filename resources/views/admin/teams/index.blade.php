@extends('admin.layouts.default')

@section('content')
  <div class="page-controls-wrap">
    <div class="page-controls-buttons-wrap">
      <a class="btn success" href="{{ route('admin.countries.teams.create') }}">
        Додати Команду
      </a>
    </div>

    @include('admin.layouts.navigation-controls')
  </div>

  <div
    class="content-table-wrap"
    id="contentTable"
    data-routes="{{ json_encode($routes) }}"
  ></div>
@endsection

@section('scripts')
  <script src="/js/admin/team-list.js"></script>
@endsection
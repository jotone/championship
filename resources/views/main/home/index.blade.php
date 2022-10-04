@extends('main.layouts.default')

@section('content')
  <div class="content-wrap">
    <div class="participant-list-wrap">
      <table class="content-table">
        <thead>
        <tr>
          <th>#</th>
          <th>Participant</th>
          <th>Score</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $i => $user)
          <tr>
            <td><span>{{ $i + 1 }}</span></td>
            <td><span>{{ $user->name }}</span></td>
            <td><span>0</span></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <main class="page-content-wrap">

    </main>

    <aside class="real-score-wrap">
      <table class="content-table">
        <thead>
        <tr>
          <th>Real Score</th>
        </tr>
        </thead>
      </table>
    </aside>
  </div>
@endsection
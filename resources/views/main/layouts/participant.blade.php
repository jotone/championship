<div class="participant-list-wrap">
  <table class="content-table participant">
    <thead>
    <tr>
      <th>#</th>
      <th>Participant</th>
      <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($results))
      @foreach($results as $i => $result)
        <tr>
          <td><a href="#">{{ $i + 1 }}</a></td>
          <td><a href="#">{{ $result->user->name }}</a></td>
          <td><a href="#">{{ $result->points }}</a></td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
</div>
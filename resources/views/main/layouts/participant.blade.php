<div class="participant-list-wrap">
  <table class="content-table participant">
    <thead>
    <tr>
      <th>#</th>
      <th>Учасники</th>
      <th>Очки</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($results))
      @foreach($results as $i => $result)
        @php
        $route = route('user.profile.show', md5($result->user_id))
        @endphp
        <tr>
          <td><a href="{{ $route }}">{{ $i + 1 }}</a></td>
          <td><a href="{{ $route }}">{{ $result->user->name }}</a></td>
          <td><a href="{{ $route }}">{{ $result->points }}</a></td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
</div>
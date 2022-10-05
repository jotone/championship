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
    @if(!empty($user_form))
      @foreach($user_form as $i => $user)
        <tr>
          <td><span>{{ $i + 1 }}</span></td>
          <td><span>{{ $user->name }}</span></td>
          <td><span>0</span></td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
</div>
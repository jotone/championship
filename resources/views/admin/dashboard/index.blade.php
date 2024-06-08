@extends('admin.layouts.default')

@section('styles')
  <link rel="stylesheet" href="/css/admin/dashboard.css">
@endsection

@section('scripts')
  <script src="/js/admin/dashboard.js"></script>
@endsection

@section('content')
  <form class="form-wrap">
    <div class="row col-100">
      <div class="col-30">
        <fieldset>
          <legend class="tac">Наступні матчі</legend>

          @foreach($next_games as $game)
            <div class="game-timer-wrap" data-start="{{ $game->start_at->timestamp }}">
              <div class="count-down-timer-wrap">
                <span class="count-down-timer-value" data-role="days" title="Дні"></span>
                <span class="count-down-timer-value" data-role="hours" title="Години"></span>
                <span class="count-down-timer-value" data-role="minutes" title="Хвилини"></span>
                {{--                <span class="count-down-timer-value" data-role="seconds" title="Секунди"></span>--}}
              </div>

              <a
                class="game-timer-teams"
                href="{{ route('admin.competitions.edit', $game->competition_id) . '?tab=groups#game' . $game->group_id . '_' . $game->id }}"
              >
                <b>{{ $game->hostTeam->ua }}</b>
                <b>-</b>
                <b>{{ $game->guestTeam->ua }}</b>
              </a>
              <div class="game-timer-starts">
                <span>{{ $game->start_at->translatedFormat('j/M/Y H:i') }}</span>
              </div>
            </div>
          @endforeach
        </fieldset>
      </div>

      <div class="col-30">
        <fieldset>
          <legend class="tac">Список учасників</legend>

          <ul class="admin-default-list">
            @foreach($forms as $form)
              <li>
                <div class="inline-wrap">
                  <div class="col-25 tac">
                    <b>{{ $form->points }}</b>
                  </div>
                  <div class="col-75 tal">
                    <a href="{{ route('user.results', md5($form->user_id)) }}">
                      {{ $form->user->name }}
                    </a>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        </fieldset>
      </div>

      <div class="col-30">
        <fieldset>
          <legend class="tac">Останняя активність</legend>

          <ul class="admin-default-list">
            @foreach($last_active as $last_active_user)
              <li>
                <div class="inline-wrap">
                  <div class="col-50 tac">
                    <b>
                      @empty($last_active_user->last_activity)
                        Не було відвідувань
                      @else
                        {{ $last_active_user->last_activity->translatedFormat('j/M/Y H:i') }}
                      @endempty
                    </b>
                  </div>
                  <div class="col-50 tal">
                    <a href="{{ route('admin.users.edit', $last_active_user->id) }}">
                      {{ $last_active_user->name }}
                    </a>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        </fieldset>
      </div>
    </div>

    <div class="row col-100">
      <fieldset class="col-100">
        <legend>Останні повідомлення на форумах</legend>

        <div class="forum-list-wrap">
          @foreach($topics as $topic)
            <div class="forum-wrap">
              <div class="forum-name">
                <a href="{{ route('admin.forum.edit', $topic->id) }}">
                  {{ $topic->name }}
                </a>
              </div>

              <ul>
                @foreach($topic->messages()->with('author')->where('pinned', 0)->orderBy('created_at', 'desc')->limit(3)->get() as $comment)
                  <li>
                    <div class="comment-data-wrap">
                      <p>
                        Автор: <a href="{{ route('admin.users.edit', $comment->author_id) }}">
                          {{ $comment->author->name }}
                        </a>
                      </p>
                      <p>Дата створення: {{ $comment->created_at->translatedFormat('j/M/Y H:i') }}</p>
                    </div>
                    <div class="comment-text">
                      {!! $comment->message !!}
                    </div>
                    <div class="comments-controls">
                      <a
                        href="{{ route('admin.forum.show', $topic->id) }}#{{ md5($comment->id) }}"
                        class="view-comment"
                        target="_blank"
                      >
                        Перейти
                      </a>
                      <a
                        href="{{ route('forum.show', $topic->url) }}#{{ md5($comment->id) }}#answer"
                        class="answer-comment"
                        target="_blank"
                      >
                        Відповісти
                      </a>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          @endforeach
        </div>
      </fieldset>
    </div>
  </form>
@endsection
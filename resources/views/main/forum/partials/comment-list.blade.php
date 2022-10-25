@foreach($comments as $comment)
  <li
    data-post="{{ md5($comment->id) }}"
    @if(Auth::check() && Auth::user()->id == $comment->author_id) class="comment-author" @endif
  >
    <div class="comment-text-wrap">
      <div class="comment-text">
        @empty($comment->deleted_at)
          {!! $comment->message !!}
        @else
          <em>Цей коментар був видалений по причині:</em>
          <span class="comment-reason">{{ $comment->edit_reason }}</span>
        @endempty
      </div>

      <div class="comment-etc-wrap">
        <span class="comment-misc" title="Автор коменатря">
          Написав: {{ $comment->author->name }}
        </span>
        <span class="comment-misc" title="Дата створення коментаря">
          {{ $comment->created_at->translatedFormat('j/F/Y о H:i') }}
        </span>

        @auth
          <a class="answer-action" href="#" title="Написати відповідь до цього коментаря">
            Відповісти
          </a>
        @endauth
      </div>
      <div class="comment-form-wrap"></div>
    </div>

    <ul>
      @includeWhen($comment->subComments->count(), 'main.forum.partials.comment-list', ['comments' => $comment->subComments])
    </ul>
  </li>
@endforeach
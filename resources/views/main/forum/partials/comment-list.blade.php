@foreach($comments as $comment)
  <li data-post="{{ md5($comment->id) }}">
    <div
      class="comment-text-wrap @if(Auth::check() && Auth::user()->id == $comment->author_id) comment-author @endif"
    >
      <div class="comment-text">
        @empty($comment->deleted_at)
          {!! $comment->message !!}
          @if (!is_null($comment->edited_at))
            <em
              title="Редаговано"
              class="{{ $comment->author_id != $comment->edited_by ? 'comment-reason' : 'comment-edited' }}"
            >
              (ред.)
            </em>
          @endif
        @else
          <em>Цей коментар був видалений</em>
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

          @if($comment->author_id == Auth::id() && $comment->created_at->addMinutes(10)->timestamp > time())
            <a
              class="comment-link"
              href="{{ route('forum-message.show', md5($comment->id)) }}"
              title="Редагувати коментар"
            >
              <i class="fas fa-edit"></i>
            </a>
            <a
              class="remove-comment-link"
              href="{{ route('forum-message.destroy', md5($comment->id)) }}"
              title="Видалити коментар"
            >
              <i class="fas fa-times"></i>
            </a>
          @endif
        @endauth
      </div>
      <div class="comment-form-wrap"></div>
    </div>

    <ul>
      @includeWhen(
        $comment->subComments->count(),
        'main.forum.partials.comment-list',
        ['comments' => $comment->subComments]
      )
    </ul>
  </li>
@endforeach
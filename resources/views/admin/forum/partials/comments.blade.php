@foreach($comments as $comment)
  <li data-id="{{ $comment->id }}" id="{{ md5($comment->id) }}">
    <form>
      @empty($comment->deleted_at)
        <textarea class="form-text" style="width: 100%" name="message">{{ $comment->message }}</textarea>
      @else
        <em>Цей коментар вуло видалено.</em>
      @endempty

      <div class="comment-etc">Автор: {{ $comment->author->name }}</div>

      <div class="comments-controls">
        @empty($comment->deleted_at)
          <a href="{{ route('api.forum-messages.update', $comment->id) }}" class="accept update" title="Змінити">
            <i class="fas fa-check"></i>
          </a>
        @else
          <a href="{{ route('api.forum-messages.update', $comment->id) }}" class="accept restore" title="Відновити">
            <i class="fas fa-sync"></i>
          </a>
        @endempty
        @if(isset($level) && $level === 1)
          <a href="{{ route('api.forum-messages.update', $comment->id) }}" class="view pin" title="Закріпити">
            <i class="{{ $comment->pinned ? 'fas' : 'far' }} fa-thumbtack"></i>
          </a>
        @endif
        <a href="{{ route('api.forum-messages.destroy', $comment->id) }}" class="remove" title="Видалити">
          <i class="fas fa-times"></i>
        </a>
      </div>
    </form>

    <ul>
      @includeWhen(
        $comment->subComments->count(),
        'admin.forum.partials.comments',
        ['comments' => $comment->subComments, 'level' => 0]
      )
    </ul>
  </li>
@endforeach
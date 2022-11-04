@extends('main.layouts.default')

@section('scripts')
  <script src="/js/ckeditor4/ckeditor.js"></script>
  <script src="/js/forum-show.js"></script>
@endsection

@section('content')
  <div class="page-heading">
    <h2>{{ $topic->name }}</h2>
  </div>

  @if(!empty($topic->img_url))
    <div class="subject-image-wrap">
      <img src="{{ $topic->img_url }}" alt="Зображення відсутнє&hellip;">
    </div>
  @endif

  <div class="subject-text-wrap">
    {!! $topic->text !!}

    <div class="subject-controls-wrap">
      <span class="subject-etc">Cтворив: {{ $topic->author->name }}</span>
      <span class="subject-etc">
        Дата створення: {{ $topic->created_at->translatedFormat($setup['comment_date_format']->value) }}
      </span>
    </div>
  </div>

  <div class="comment-list-wrap">
    @auth
      @if($topic->messages->count())
        <div class="add-comment-wrap">
          <button name="showCommentForm" type="button">
            <span>Коментувати</span>
          </button>
        </div>
      @endif
    @endauth

    <ul>
      @include('main.forum.partials.comment-list', ['comments' => $topic->messages])
    </ul>

      @auth
        <div class="add-comment-wrap">
          <button name="showCommentForm" type="button">
            <span>Коментувати</span>
          </button>

          <form id="comment-form" action="{{ route('forum-message.store') }}" method="post">
            @csrf
            <textarea name="message" required></textarea>
            <div class="comment-form-misc">
              <span>Пише {{ Auth::user()->name }}</span>

              <button type="submit">Готово</button>
            </div>
          </form>
        </div>
      @endauth
  </div>
@endsection
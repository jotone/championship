@extends('main.layouts.default')

@section('styles')
  @isset($jwt)
    <meta name="jwt" content="{{ $jwt }}">
  @endif
  <link rel="stylesheet" href="/css/regular.css">
@endsection

@section('scripts')
  <script src="/js/forum-topics.js"></script>
@endsection

@section('content')
  <div class="page-heading">
    <h1>Розділи форуму</h1>
  </div>

  @isset($jwt)
    <div id="forumList" data-routes="{{ json_encode($routes) }}"></div>
  @else
    <ul class="forum-list-wrap">
      @foreach($topics as $topic)
        <li @if($topic->pinned) data-pin @endif>
          <div class="forum-list-item">
            @if(!empty($topic->img_url))
              <a href="#">
                <img src="{{ $topic->img_url }}" alt="Зображення відсутнє&hellip;">
              </a>
            @endif
            <div class="forum-list-body">
              <a class="forum-list-title" href="#">
                {!! $topic->name !!}
              </a>

              <div class="forum-list-description">
                {{ mb_ucfirst($topic->description) }}
              </div>
            </div>
          </div>

          <div class="forum-list-info">
            <span class="forum-list-text">Cтворив: {{ $topic->author->name }}</span>
            <span class="forum-list-text">Комментарів: {{ $topic->messages->count() }}</span>
          </div>
        </li>
      @endforeach
    </ul>
  @endisset
@endsection
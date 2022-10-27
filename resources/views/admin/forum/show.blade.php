@extends('admin.layouts.default')

@section('styles')
  <link rel="stylesheet" href="/css/regular.css">
@endsection

@section('scripts')
  <script src="/js/admin/forum-messages-list.js"></script>
@endsection

@section('content')
  <div class="comment-list-wrap">
    <ul>
      @include('admin.forum.partials.comments', ['level' => 1])
    </ul>
  </div>
@endsection
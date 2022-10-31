@extends('main.layouts.simple')

@section('styles')
  <link rel="stylesheet" href="/css/summary.css">
@endsection

@section('scripts')
  <script src="/js/summary.js"></script>
@endsection

@section('content')
  <textarea style="display: none" name="models">{{ base64_encode(json_encode($groups)) }}</textarea>
  <div id="summary-wrapper"></div>
@endsection
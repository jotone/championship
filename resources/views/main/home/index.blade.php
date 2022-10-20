@extends('main.layouts.default')

@section('content')
  @isset($page_data)
    {!! $page_data->content !!}
  @endisset
@endsection
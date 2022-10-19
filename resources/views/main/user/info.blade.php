@extends('main.layouts.default')

@section('content')
  <div class="user-info-wrap">
    <a href="{{ route('user.results', md5($user->id)) }}" class="user-name">
      <h1>Переглянути анкету {{ $user->name }}</h1>
    </a>

    <a href="{{ route('user.results', md5($user->id)) }}" class="image-wrap">
      @if(!empty($user->img_url))
        <img src="{{ $user->img_url }}" alt="">
      @endif
    </a>

    <div class="user-score-wrap">
      <span>Набрані очки - "{{ $user->forms()->where('competition_id', 1)->value('points') }}"</span>
    </div>
  </div>

  <div class="user-about-wrap">
    <div class="user-about-caption-wrap">
      <span>ІНФОРМАЦІЯ ПРО УЧАСНИКА: *{{ $user->name }}*</span>
    </div>
    @empty($user->info)
      <h6>Інформація не внесена</h6>
    @else
      <h6>{{ $user->info }}</h6>
    @endempty
  </div>
@endsection
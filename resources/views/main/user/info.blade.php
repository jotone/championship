@extends('main.layouts.default')

@section('content')
  <div class="user-info-wrap">
    <div class="user-name">
      <h1>{{ $user->name }}</h1>
    </div>

    <div class="image-wrap">
      @if(!empty($user->img_url))
        <img src="{{ $user->img_url }}" alt="">
      @endif
    </div>

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
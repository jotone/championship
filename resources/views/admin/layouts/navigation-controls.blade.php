<div class="page-controls-navigation">
  <form class="search-form-wrap" method="GET" action="{{ $routes['index'] }}">
    <input
      name="search"
      type="search"
      placeholder="Search&hellip;"
      pattern=".{0}|.{3,}"
      value="{{ $search ?? '' }}"
    >
    <button type="submit"><i class="fas fa-search"></i></button>
  </form>

{{--  <div class="per-page-wrap">--}}
{{--    <select name="perPage" class="form-select">--}}
{{--      @foreach([10, 25, 50, 100, 250] as $i)--}}
{{--        <option value="{{ $i }}">{{ $i }}</option>--}}
{{--      @endforeach--}}
{{--    </select>--}}
{{--  </div>--}}
</div>
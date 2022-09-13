
<ul>
  @foreach($menu as $item)
    @php
      $item_props = !is_null(Route::getRoutes()->getByName($item->url))
          ? explode('@', Route::getRoutes()->getByName($item->url)->action['uses'])
          : [];
    @endphp

    @if(
        $item->is_section
        || $user->role->slug == 'superadmin'
        || (
            isset($user->permissions[$item_props[0]])
            && in_array($item_props[1], $user->permissions[$item_props[0]]['allowed_methods'])
        )
    )
      <li>
        @if($item->is_section)
          <div>
            @if(!empty($item->img_url))
              <i class="image {{ $item->img_url }}"></i>
            @endif
            <span>{{ $item->name }}</span>
            <i class="fas fa-caret-right arrow"></i>
          </div>
        @else
          <a href="{{ route($item->url) }}">
            @if(!empty($item->img_url))
              <i class="image {{ $item->img_url }}"></i>
            @endif
            <span>{{ $item->name }}</span>
          </a>
        @endif

        @if($item->subMenus->count())
          @include('admin.layouts.side-menu', ['menu' => $item->subMenus])
        @endif
      </li>
    @endif
  @endforeach
</ul>
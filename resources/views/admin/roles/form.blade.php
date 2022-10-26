@extends('admin.layouts.default')

@section('content')
  <div class="form-wrap">
    <form
      action="{{ isset($model) ? route('api.roles.update', $model->id) : route('api.roles.store') }}"
      data-xhr
      data-msg="Role.name"
      method="POST"
    >
      @isset($model)
        @method('PUT')
      @endisset
      @csrf

      <div class="row col-100">
        <fieldset class="col-30">
          <legend>Основна інформація</legend>

          <div class="form-row">
            <label class="caption">
              <span>Role name:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="name"
                data-slug="input.slug"
                placeholder="Role name&hellip;"
                required
                value="{{ $model->name ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Role slug:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="slug"
                placeholder="Role slug&hellip;"
                required
                value="{{ $model->slug ?? '' }}"
              >
            </label>
          </div>

          <div class="form-row">
            <label class="caption">
              <span>Role level:</span>

              <input
                autocomplete="off"
                class="form-input col-100"
                name="level"
                placeholder="Role level&hellip;"
                max="255"
                min="{{ $user->role->level }}"
                type="number"
                value="{{ $model->level ?? '' }}"
              >
            </label>
          </div>
        </fieldset>

        <fieldset class="col-70">
          <legend>Permission list</legend>

          <table class="info-table">
            <thead>
            <tr>
              <th>
                <span>Controller</span>
              </th>
              <th>
                <span>Type</span>
              </th>
              <th>
                <span>Methods</span>
              </th>
            </tr>
            </thead>

            <tbody>
              @foreach($permissions as $permission)
                @if($user->role->slug == 'superadmin' || isset($user->permissions[$permission['class']]))
                  <tr>
                    <td class="marked" title="{{ $permission['class'] }}">
                      <span>{{ substr($permission['file'], 0, -4) }}</span>
                    </td>
                    <td class="marked">
                      <span>
                        {{ $permission['parent'] == 'App\Http\Controllers\BasicAdminController' ? 'Page' : 'API' }}
                      </span>
                    </td>
                    <td>
                      <div class="row">
                        @foreach($permission['methods'] as $method)
                          @if(
                            $user->role->slug == 'superadmin'
                            || in_array($method, $user->permissions[$permission['class']]['allowed_methods'])
                          )
                            <label class="inline-caption">
                              <input
                                name="permissions[{{ $permission['class'] }}][{{ $method }}]"
                                type="checkbox"
                                @if(
                                  isset($model) &&
                                  isset($model_permissions[$permission['class']]) &&
                                  in_array($method, $model_permissions[$permission['class']]['allowed_methods'])
                                )
                                  checked
                                @endif
                              >
                              <span>{{ ucfirst($method) }} </span>
                            </label>
                          @endif
                        @endforeach
                      </div>
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </fieldset>
      </div>
      <div class="row">
        <button type="submit" class="btn success">
          Зберегти
        </button>
      </div>
    </form>
  </div>
@endsection
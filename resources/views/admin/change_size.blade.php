@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
            @if (session('status'))
              <!-- флеш сообщение -->
              <div class="alert alert-success text-center">
                {{ session('status') }}
              </div>
            @endif
                <div class="card-header">
                  <div class="d-flex justify-content-between align-items-center">
                    Изменение лимита
                  </div>
                </div>
                <div class="row justify-content-center">
                        <div class="col-8">
                            <br/>
                            <form  action="{{ route('size_update') }}" method="post">
                                {{ csrf_field() }}
                                @method('PATCH')
                                <input type="hidden" name="user" value="{{$User->id}}" />
                                <div class="form-group row">
                                    <label for="last_pas" class="col-md-4 col-form-label text-md-right">Лимит</label>
                                    <div class="col-md-4">
                                        <input id="last_pas" type="number" value="{{ $size }}" max="{{$max_size}}" min="{{ $min_size }}" class="form-control @isset($my) {{'is-invalid'}} @endisset" name="size_user" required autofocus/>
                                       
                                    </div>
                                    <label class="col-md-2 col-form-label text-md-left">МБ</label>
                                    @isset($my)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $my }}
                                            </strong>
                                        </span>
                                    @endisset
                                </div>
                                <div class="form-group row justify-content-center">
                                    <div class="col-md-4 offset-md-0">
                                        <button class="btn btn-block btn-primary" type="submit" name="button">Сохранить</button>
                                    </div> 
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('nav-link')
<li class="nav-item menu-logo">
<a class="nav-link"  href="{{ route('admin_panel') }}" role="button">
    <div class="menu-admin-panel" title="Админ. панель"></div>
  </a>
</li>
<li class="nav-item menu-logo">
<a class="nav-link"  href="{{ route('admin_password') }}" role="button">
    <div class="menu-change-password" title="Изменить пароль"></div>
  </a>
</li>
<li class="nav-item menu-logo">
    <div class="menu-space" ></div>
</li>

@endsection

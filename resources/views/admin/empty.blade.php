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
                   Результаты поиска
                  </div>
                </div>
                <div class="row justify-content-center">
                        <div class="col-8">
                            <br/>
                            <p class="text-center">Поиск не дал результата</p>
                            <div class="row justify-content-center">
                                <a class="btn btn-info" href="{{ route('admin_panel') }}" >Назад</a>
                            </div>  
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

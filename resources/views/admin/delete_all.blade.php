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
                <div id="app">
                  <my-delete user="{{ $user_login }}" :groups="{{ $groups }}" :folders="{{ $folders }}" :files="{{ $files }}"  :usize="{{ $user_size }}" :count_folder="{{ $count_folder }}" :count_file="{{ $count_file }}" :count_groups="{{ $count_groups }}"><my-delete>
                </div>    <!-- конец блока -->
                </div>
            <!-- продалжение карточки -->
            
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

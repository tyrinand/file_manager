@extends('layouts.app_out_nav_bar')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">Облачное хранилище</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Авторизация</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Логин</label>

                            <div class="col-md-6">
                                <input id="login" type="text" class="form-control @isset($my) {{'is-invalid'}} @endisset" name="login" value="{{ old('login') }}" required autofocus>
                               
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="password" class="col-md-3 col-form-label text-md-right">Пароль</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @isset($my) {{'is-invalid'}} @endisset"
                                 name="password" required/>

                                 @isset($my)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>
                                            {{ $my }}
                                        </strong>
                                    </span>
                                @endisset
                            </div>
                        </div>


                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-md-3 ">
                                <button type="submit" class="btn btn-primary form-control">
                                        Войти
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
               
            </div>
        </div>
    </div>
</div>    
@endsection
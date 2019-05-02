@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Загрузка файла: {{ $file->user_name }}</div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <p class="text-center">Для загрузки авторизуйтесь</p>
                            <form method="POST" action="{{ route('download_form_login') }}">
                                <input type="hidden" name="file" value="{{  $file->slug }}" />
                                @csrf
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Логин</label>
                                    <div class="col-md-6">
                                        <input id="login" type="text" class="form-control" name="login"  required autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>
                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" required/>
                                        </div>
                                </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary form-control">Войти</button>
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

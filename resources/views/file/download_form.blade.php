<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Облако</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
            </div>
        </nav>
        <main class="py-4">
        <div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Загрузка файла: {{ $file->user_name }}</div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                        @if ($file->public_url === 1)
                            <p class="text-center">Для загрузки авторизуйтесь</p>
                            <form method="POST" action="{{ route('download_form_login') }}">
                                <input type="hidden" name="file" value="{{  $file->slug }}" />
                                @csrf
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Логин</label>
                                    <div class="col-md-6">
                                        <input id="login" type="text" class="form-control @isset($my) {{'is-invalid'}} @endisset" name="login"  required autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>
                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @isset($my) {{'is-invalid'}} @endisset" name="password" required/>
                                            
                                            @isset($my)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                    {{ $my }}
                                                    </strong>
                                                </span>
                                            @endisset
                                        </div>
                                </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary form-control">Скачать</button>
                                </div>
                            </div>
                            @isset($my)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $my}}</strong>
                                </span>
                            @endisset
                        </form>
                        @else
                            <p class="text-center">Файл изъят из публичного доступа</p>
                        @endif    
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
        </main>
    </div>
</body>
</html>

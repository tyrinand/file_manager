@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-8">
        <form  action="{{ route('group_store') }}" method="post">
            {{ csrf_field() }}
        <div class="form-group">
        <br/>
            <input type="text" class="form-control" name="title" placeholder="Имя группы" required/>
        </div>
        <div class="row justify-content-center">
            <button class="btn btn-primary" type="submit" name="button">Добавить</button>
            <span class="myspace"></span>
                <a class="btn btn-info" href="{{ route('user_group') }}">Назад</a>
        </div>
        </form>
    </div>
</div>
@endsection

@section('progress')
<div class="flex-progress">
    <div class="progress" style="height: 35px;">
    <?php
        $posent =  (Auth::user()->use_size /  ((Auth::user()->size)*1048576))*100;
    ?>
            <div class="progress-bar progress-bar-striped  " role="progressbar" 
            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{ $posent }}%" >
            </div>
            
    </div>
    <p class=" my_prosent_label">{{ ceil(Auth::user()->use_size/1048576) }}  / {{ Auth::user()->size }} Mb</p>
</div>    
@endsection
@section('nav-link')
<li class="nav-item menu-logo">
  <a class="nav-link"  href="{{ route('root_folder') }}" role="button">
    <div class="menu-folder-home" title="Корневой каталог"></div>
  </a>
</li>
@endsection


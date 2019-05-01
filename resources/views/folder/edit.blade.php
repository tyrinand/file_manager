@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Переименовать</div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <form  action="{{ route('folder_update', $folder->slug) }}" method="post">
                                {{ csrf_field() }}
                                @method('PATCH')
                                <div class="form-group">
                                    <br/>
                                    <input type="text" class="form-control" name="user_name" placeholder="Имя папки" required value="{{$folder->user_name}}"/>
                                </div>
                                <input type="hidden" name="parent" value="{{ $parent_folder->slug }}" />
                                <div class="row justify-content-center">
                                        <button class="btn btn-primary" type="submit" name="button">Сохранить</button>
                                        <span class="myspace"></span>
                                        <a class="btn btn-info" href="{{ route('folder_child', $parent_folder->slug) }}">Отмена</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
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


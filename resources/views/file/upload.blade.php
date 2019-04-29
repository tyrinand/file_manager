@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Загрузка файлов</div>
                    <div class="row justify-content-center">
                        <div class="col-10">
                           <div id="app">
                                <upload-file :folder="{{ $folder->id }}" :totaluser="{{ Auth::user()->size }}" :usesize="{{ Auth::user()->use_size }}"  > </upload-file>
                           </div>
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
        $posent =  (Auth::user()->use_size /  Auth::user()->size)*100;
    ?>
            <div class="progress-bar progress-bar-striped  " role="progressbar" 
            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{ $posent }}%" >
            </div>
            
    </div>
    <p class=" my_prosent_label">{{Auth::user()->use_size}}  / {{ Auth::user()->size }} Mb</p>
</div>    
@endsection

@section('nav-link')
<li class="nav-item menu-logo">
    <a class="nav-link"  href="{{ route('root_folder') }}" role="button">
        <div class="menu-folder-home" title="Корневой каталог"></div>
  </a>
</li>
<li class="nav-item menu-logo">
    <div class="menu-space" ></div>
</li>

@endsection

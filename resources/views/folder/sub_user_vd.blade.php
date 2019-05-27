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
                <div class="card-header">Публикация папки "{{ $folder->title }}" в группе "{{$grop->title }}"</div>
                <div class="row justify-content-center">
                  <div class="col-12"> 
                    <div id="app">
                        <my-sub folder="{{ $folder->slug }}" group="{{ $grop->slug }}"> </my-sub>
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
  <a class="nav-link"
  @if ($parent_folder->root === 0)
   href="{{ route('folder_parent',$parent_folder) }}" 
  @else
    href="{{ route('root_folder') }}"
  @endif
   role="button"> 
    <div class="menu-folder-up" title="Вверх"></div>
  </a>
</li>
@endsection

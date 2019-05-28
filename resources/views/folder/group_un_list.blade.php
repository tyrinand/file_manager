@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $folder->title }}</div>
                 @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="row justify-content-center">

                    <div class="col-8">
                        <br/>
                        <form  action="{{ route('un_sub_user_form') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="folder" value="{{ $folder->slug }}" />
                                <div class="form-group"> 
                                    <select class="form-control"  name="group" required>
                                        <option value=""  disabled selected>Выберете группу</option>
                                        @foreach ($list_group as $gr)
                                            <option value="{{$gr->slug}}" >{{ $gr->title }}({{ $gr->user_login }}) </option>
                                        @endforeach
                                    </select>    
                                </div>            
                                <div class="row justify-content-center">
                                    <button class="btn btn-primary" type="submit" name="button">Отписаться</button>
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

@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $folder_title }}</div>
                @if ( ($children_folder->isNotEmpty()) )
                    <table class="table table-bordered table-sm my-table">
                      <thead>
                        <tr>
                          <th>Имя</th>
                          <th>Дата создания</th>
                          <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach ($children_folder as $fl)
                          <tr>
                            <td>
                              <a href="{{ route('folder_child',$fl->slug) }}" class="folder-link">  
                                <div class="folder-container" >
                                    <div class="folder modile-icons" ></div>
                                    <span class="my-min-space"></span>
                                    {{ $fl->user_name }}
                                </div>    
                              </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($fl->created_at)->format('d.m.Y') }} </td>
                            <td>
                              <form class="" action="{{ route('folder_delete',$fl->slug) }}" method="post" onsubmit="if(confirm('Удалить?')){return true}else{return false}">
                                <input type="hidden" name="_method" value="DELETE">
                                  {{ csrf_field() }}
                                <button type="submit" class="my-submit-btn">
                                  <div class="icon-folder-delete modile-icons" title="Удалить папку"></div>
                                </button>
                                <a href="{{ route('folder_edit',$fl->slug) }}">  
                                    <div class="folder-edit modile-icons" title="Переименовать папку"></div>  
                                </a>
                              </form>
                            </td> 
                          </tr>
                          @endforeach
                        </tbody>
                    </table>
                @else
                <br>
                    <p class="text-center">Папка пуста</p>
                @endif
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
  <a class="nav-link" href="{{ route('folder_create',$parent_folder) }}"  role="button">
    <div class="menu-create-folder" title="Создать папку"></div>
  </a>
</li>
<li class="nav-item menu-logo">
  <a class="nav-link" href="{{ route('file_upload',$parent_folder) }}" role="button">
    <div class="menu-upload-files" title="Загрузить файл"></div>
  </a>
</li>
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
<li class="nav-item menu-logo">
  <a class="nav-link"  href="{{ route('root_folder') }}" role="button">
    <div class="menu-folder-home" title="Корневой каталог"></div>
  </a>
</li>
@endsection

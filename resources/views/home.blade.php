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
                <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                  {{ $folder_title }}
                  @if (($children_file->isNotEmpty()) )
                    <form class="" action="{{ route('all_input') }}" method="post" onsubmit="if(confirm('Удалить файлы из текущего каталога ?')){return true}else{return false}">
                      <input type="hidden" name="_method" value="DELETE">
                      <input type="hidden" name="slug" value="{{ $parent_folder->slug }}">
                        {{ csrf_field() }}
                        <button type="submit" class="my-submit-btn">
                          <div class="all-input-backet modile-icons" title="Удаление файлов из текущего каталога"></div>
                        </button>
                      </form>   
                    @endif
                </div>
                </div>
                @if ( ($children_folder->isNotEmpty()) || ($children_file->isNotEmpty()) )
                    <table class="table table-bordered table-sm my-table table-condensed">
                      <thead>
                        <tr>
                          <th>
                            <div class="my-wrap-find-string"> <!-- поиск по файлам и папкам -->
                                <form class="search" action="{{ route('serch_str') }}" method="POST"> 
                                {{ csrf_field() }}  
                                <input type="hidden" name="parent_folder" value="{{ $parent_folder->slug }}"/>
                                  <section class="flexbox">
                                    <span class="input-find-caption">Имя</span>
                                    <div class="input-class">
                                      <input type="text" name="str_find" required value="@if(!empty($str_find)){{$str_find}}@endif"/>
                                      <button class="btn-sm btn btn-primary my-btn-find-string" type="submit" name="button"></button>
                                    </div>
                                  </section>
                                </form>
                            </div> <!-- поиск по файлам и папкам --> 
                          </th>
                          <th class="d-none d-md-table-cell">Дата создания</th>
                          <th>Размер</th>
                          <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach ($children_folder as $fl)
                          <tr>
                            <td >
                              <a href="{{ route('folder_child',$fl->slug) }}" class="folder-link">  
                                <div class="folder-container" >
                                  @if( ($fl->root_mount === 0) && ( $fl->public_folder === 0 ) )
                                    <div class="folder modile-icons" title="Локальная папка"></div>
                                  @elseif(($fl->root_mount === 0) && ( $fl->public_folder === 1 ))
                                    <div class="folder-public modile-icons" title="Публичная папка"></div>
                                  @else
                                    <div class="folder-public-root modile-icons" title="Корень монитирования"></div>
                                  @endif
                                    <span class="my-min-space"></span>
                                    <?php 
                                          if(strlen($fl->user_name) > 45)
                                          {
                                            $new_p1 = mb_substr($fl->user_name, 0, 20,'UTF-8');
                                            $new_p2 = mb_substr($fl->user_name, -20, 20,'UTF-8');
                                            $new_name = $new_p1."...".$new_p2;
                                          }
                                          else
                                          {
                                            $new_name = $fl->user_name;
                                          }
                                    ?>
                                    <span class="my-file-name">{{$new_name }}</span>
                                </div>    
                              </a>
                            </td>
                            <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($fl->created_at)->format('d.m.Y') }} </td>
                            <td></td>
                            <td> <!-- действия для папок -->
                              <form class="" action="{{ route('folder_delete',$fl->slug) }}" method="post" onsubmit="if(confirm('Удалить?')){return true}else{return false}">
                                <input type="hidden" name="_method" value="DELETE">
                                  {{ csrf_field() }}
                                <button type="submit" class="my-submit-btn">
                                  <div class="icon-folder-delete modile-icons" title="Удалить папку"></div>
                                </button>
                                <a href="{{ route('folder_edit',$fl->slug) }}">  
                                    <div class="folder-edit modile-icons" title="Переименовать папку"></div>  
                                </a>
                                <a href="{{ route('folder_list_group',$fl->slug) }}">  
                                    <div class="folder-share modile-icons" title="Опубликовать папку"></div>  
                                </a>
                                @if( ($fl->root_mount === 1) && ( $fl->public_folder === 1 ) )
                                  <a href="{{ route('folder_un_list_group',$fl->slug) }}">  
                                      <div class="folder-close modile-icons" title="Отписать папку"></div>  
                                  </a>
                                @endif
                              </form>
                            </td> 
                          </tr>
                          @endforeach
                          @foreach ($children_file as $fl)
                          <tr>
                            <td >
                                <div class="file-container" >
                                      @if ($fl->public_url === 1)
                                          <div class="file-public modile-icons" title="Публичный файл"></div>
                                      @else
                                          <div class="file modile-icons" title="Локальный файл"></div>
                                      @endif
                                    <span class="my-min-space"></span>
                                    <?php 
                                          if(strlen($fl->user_name) > 45)
                                          {
                                            $new_p1 = mb_substr($fl->user_name, 0, 20,'UTF-8');
                                            $new_p2 = mb_substr($fl->user_name, -20, 20,'UTF-8');
                                            $new_name = $new_p1."...".$new_p2;
                                          }
                                          else
                                          {
                                            $new_name = $fl->user_name;
                                          }
                                    ?>
                                    <span class="my-file-name">{{ $new_name }}</span>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ \Carbon\Carbon::parse($fl->created_at)->format('d.m.Y') }} 
                            </td>
                            <td>
                              @if ($fl->size < 1024)
                                  {{ $fl->size }} байт
                              @elseif ($fl->size < 1048576)
                                    {{ ceil($fl->size/1024) }} КБ
                              @else
                                    {{ ceil($fl->size/1048576) }} МБ
                              @endif
                            </td>
                            <td> <!-- действия для файлов -->
                            <form class="" action="{{ route('delete_basket',$fl->slug) }}" method="post" onsubmit="if(confirm('Удалить?')){return true}else{return false}">
                                <input type="hidden" name="_method" value="DELETE">
                                 {{ csrf_field() }}
                                <a href="{{ route('master_download',$fl->slug) }}">  
                                    <div class="download-file modile-icons" title="Скачать"></div>  
                                </a>
                                <a href="{{ route('share',$fl->slug) }}">  
                                    <div class="share-file modile-icons" title="Поделиться"></div>  
                                </a>
                                <a href="{{ route('file_close',$fl->slug) }}">  
                                    <div class="close-file modile-icons" title="Закрыть доступ"></div>  
                                </a>
                                <button type="submit" class="my-submit-btn">
                                  <div class="icon-file-delete modile-icons" title="Удалить файл"></div>
                                </button>
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
<li class="nav-item menu-logo">
  <a class="nav-link" href="{{ route('basket') }}" role="button">
    <div class="menu-trash" title="Корзина"></div>
  </a>
</li>
<li class="nav-item menu-logo">
  <a class="nav-link" href="{{ route('user_group') }}" role="button">
    <div class="menu-grop" title="Группы"></div>
  </a>
</li>
@endsection

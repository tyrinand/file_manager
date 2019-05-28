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
                </div>
                </div>
                @if ( ($children_folder->isNotEmpty()) || ($children_file->isNotEmpty()) )
                    <table class="table table-bordered table-sm my-table table-condensed">
                      <thead>
                        <tr>
                          <th>
                            <div class="my-wrap-find-string"> 
                              <span>Имя</span>
                            </div> 
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
                              <a href="{{ route('child',['folder' => $fl->slug, 'group' => $group->slug]) }}" class="folder-link">  
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
                              <a href="{{ route('gr_master_download',['file' => $fl->slug, 'group' => $group->slug]) }}">  
                                <div class="download-file modile-icons" title="Скачать"></div>  
                              </a>
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
  <a class="nav-link"  href="{{ route('group_open',$group->slug) }}" role="button">
    <div class="menu-group-home" title="Содержимое группы"></div>
  </a>
</li>
<li class="nav-item menu-logo">
  <a class="nav-link"
  @if ($parent_folder->root_mount === 0)
   href="{{ route('group_parent',['folder' =>$parent_folder->slug, 'group' => $group->slug]) }}" 
  @else
    href="{{ route('group_open',$group->slug) }}"
  @endif
   role="button"> 
    <div class="menu-folder-up" title="Вверх"></div>
  </a>
</li>
<li class="nav-item menu-logo">
  <a class="nav-link"  href="{{ route('root_folder') }}" role="button">
    <div class="menu-folder-home" title="Главное окно"></div>
  </a>
</li>
<li class="nav-item menu-logo">
  <a class="nav-link" href="{{ route('user_group') }}" role="button">
    <div class="menu-grop" title="Группы"></div>
  </a>
</li>
@endsection

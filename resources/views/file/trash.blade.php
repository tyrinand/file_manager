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
                <div class="card-header">Корзина</div>
                @if ( ($children_file->isNotEmpty()) )
                    <table class="table table-bordered table-sm my-table table-condensed">
                      <thead>
                        <tr>
                          <th>Имя</th>
                          <th class="d-none d-md-table-cell">Родительской каталог</th>
                          <th>Размер</th>
                          <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach ($children_file as $fl)
                          <tr>
                            <td >
                                <div class="file-container" >
                                    <div class="file modile-icons" title="Локальный файл"></div>
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
                            <form class="" action="#" method="post" onsubmit="if(confirm('Удалить?')){return true}else{return false}">
                                <input type="hidden" name="_method" value="DELETE">
                                 {{ csrf_field() }}
                                <a href="#">   <!--  href="{{ route('share',$fl->slug) }}" -->
                                    <div class="download-file modile-icons" title="Восстановить"></div>  
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
                    <p class="text-center">Корзина пуста</p>
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
    <div class="menu-space" ></div>
</li>

@endsection

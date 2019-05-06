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
                    <span>Корзина</span>
                    @if (($children_file->isNotEmpty()) )
                    <form class="" action="{{ route('clear_basket') }}" method="post" onsubmit="if(confirm('Очистить?')){return true}else{return false}">
                      <input type="hidden" name="_method" value="DELETE">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger">
                          Очистить
                          @if ($total_size < 1024)
                            {{ $total_size }} байт
                         @elseif ($total_size < 1048576)
                            {{ ceil($total_size/1024) }} КБ
                         @else
                            {{ ceil($total_size/1048576) }} МБ
                          @endif                      
                          </button>
                      </form>   
                    @endif
                  </div>
                
                </div>
                @if ( ($children_file->isNotEmpty()) )
                    <table class="table table-bordered table-sm my-table table-condensed">
                      <thead>
                        <tr>
                          <th>Имя</th>
                          <th class="d-none d-md-table-cell">Путь к файлу</th>
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
                                {{ $fl->folder->title }} 
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
                            <form class="" action="{{ route('file_clear') }}" method="post" onsubmit="if(confirm('Удалить?')){return true}else{return false}">
                                <input type="hidden" name="_method" value="DELETE">
                                 {{ csrf_field() }}
                                <input type="hidden" name="slug" value="{{ $fl->slug }}"/> 
                                <a  href="{{ route('rest',$fl->slug) }}">   <!--  -->
                                    <div class="restore-file modile-icons" title="Восстановить"></div>  
                                </a>
                                <button type="submit" class="my-submit-btn">
                                  <div class="delete-all-file modile-icons" title="Удалить файл"></div>
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
<a class="nav-link"  href="{{ route('root_folder') }}" role="button">
    <div class="menu-folder-home" title="Корневой каталог"></div>
  </a>
</li>
<li class="nav-item menu-logo">
    <div class="menu-space" ></div>
</li>

@endsection

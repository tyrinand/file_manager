@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Созданные группы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Публичные папки</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Вы подписаны на </a>
                </li>
            </ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="container"> <!-- главная строка -->
        
        <div class="row justify-content-center"><!-- сообщение -->
          <div class="col-12">
            <div class="alert alert-secondary text-center" role="alert">
              Вы не можете удалять участников, только распустить группу
            </div>
          </div>
        </div><!-- сообщение --> 

        @if ( $created_groups->isEmpty())
          <p class="text-center">Нет созданных групп</p>
        @else
            <div class="row justify-content-center"><!-- таблица -->
              <div class="col-md-12 col-12">
                <table class="table table-bordered table-sm my-table table-condensed">
                  <thead>
                    <tr>
                      <th>Название</th>
                      <th>Кол-во подписчиков</th>
                      <th>Кол-во публичных папкок</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($created_groups as $gr)
                    <tr>
                      <td>{{ $gr->title }}</td>
                      <td>{{ $gr->user_sub_count }}</td>
                      <td>{{ $gr->public_folder_count }}</td>
                      <td>
                          <a href="{{ route('group_share',$gr->slug) }}">  
                            <div class="share-group modile-icons" title="Отправить ссылку на вступление"></div>  
                          </a>
                          <a href="">  
                            <div class="delete-group modile-icons" title="Удалить группу"></div>  
                          </a>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>    
                  </table>
              </div>
            </div><!-- таблица -->  
        @endif
        <div class="row justify-content-center">
            <a class="btn btn-info text-center" href="{{ route('group_create') }}">Создать группу</a>
        </div>      
    </div>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="col-12">
            @if ( $public_folder->isEmpty() )
                <div class="alert alert-secondary text-center" role="alert">
                    У вас нет публичных папок
                </div>
            @else

            @endif
        </div>
  </div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
  <div class="container"> <!-- главная строка -->
        @if ( $inside_group->isEmpty())
        <div class="row justify-content-center"><!-- сообщение -->
          <div class="col-12">
            <div class="alert alert-secondary text-center" role="alert">
                Вы не вступали в группы
            </div>
          </div>
        </div><!-- сообщение --> 
        @else
        <div class="row justify-content-center"><!-- сообщение -->
          <div class="col-12">
            <div class="alert alert-secondary text-center" role="alert">
                Выход из группы возможен, если администратор распустит ее
            </div>
          </div>
        </div><!-- сообщение --> 
            <div class="row justify-content-center"><!-- таблица -->
              <div class="col-md-8 col-12">
                <table class="table table-bordered table-sm my-table table-condensed">
                  <thead>
                    <tr>
                      <th>Название</th>
                      <th>Владелец</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($inside_group as $gr)
                    <tr>
                      <td>{{ $gr->group->title }}</td>
                      <td>{{ $gr->group->user_login }}</td>
                    </tr>
                  @endforeach
                  </tbody>    
                  </table>
              </div>
            </div><!-- таблица -->  
        @endif  
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
  <a class="nav-link"  href="{{ route('root_folder') }}" role="button">
    <div class="menu-folder-home" title="Корневой каталог"></div>
  </a>
</li>
@endsection


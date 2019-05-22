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
                    <span class="my-table">Кол-во пользователей: {{$count_user}}</span>
                    <span class=my-table>Использовано:
                                @if ( $use_size_total< 1024)
                                    {{ $use_size_total }} байт
                                @elseif ( $use_size_total < 1048576)
                                    {{ ceil( $use_size_total/1024) }} КБ
                                @else
                                    {{ ceil( $use_size_total/1048576) }} МБ
                                @endif
                     </span>
                  </div>
                </div>
                @if ( ($users->isNotEmpty()) )
                    <table class="table table-bordered table-sm my-table table-condensed">
                      <thead>
                        <tr>
                          <th>
                          <div class="my-wrap-find-string"> <!-- поиск по логину -->
                                <form class="search" action="{{ route('serch_login') }}" method="POST"> 
                                {{ csrf_field() }}  
                                  <section class="flexbox">
                                    <span class="input-find-caption">Логин</span>
                                    <div class="input-class">
                                      <input type="text" name="str_find_login" required value="@if(!empty($str_find_login)){{$str_find_login}}@endif"/>
                                      <button class="btn-sm btn btn-primary my-btn-find-string" type="submit" name="button"></button>
                                    </div>
                                  </section>
                                </form>
                            </div> <!-- поиск по логину --> 
                          </th>
                          <th class="d-none d-md-table-cell">Использовано</th>
                          <th>Лимит</th>
                          <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach ($users as $user)
                          <tr>
                            <td>
                               {{ $user->login }} 
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if ( $user->use_size < 1024)
                                    {{  $user->use_size }} байт
                                @elseif ( $user->use_size < 1048576)
                                    {{ ceil( $user->use_size/1024) }} КБ
                                @else
                                    {{ ceil( $user->use_size/1048576) }} МБ
                                @endif
                            </td>
                            <td>
                                {{ $user->size }} МБ
                            </td>
                            <td> <!-- действия для файлов -->
                                 @if ( $user->enable == 0 )
                                 <a  href="{{ route('block_user',$user->id) }}">   <!--  -->
                                    <div class="block-user modile-icons" title="Открыть доступ"></div>  
                                 </a>
                                 @else
                                 <a  href="{{ route('block_user',$user->id) }}">   <!--  -->
                                    <div class="unblock-user modile-icons" title="Закрыть доступ"></div>  
                                 </a>
                                 @endif
                                
                                <a  href="{{ route('user_size_form',$user->id) }}">   <!--  -->
                                    <div class="change-size modile-icons" title="Изменить доступный лимит"></div>  
                                </a>
                                <a  href="{{ route('admin_delete_all',$user->id) }}">   <!--  -->
                                    <div class="delete-all-file-admin modile-icons" title="Удалить все файлы пользователя"></div>  
                                </a>
                            </td> 
                          </tr>
                          @endforeach
                        </tbody>
                    </table>
                    <div class="row justify-content-center">
                            {{ $users->appends(request()->input())->links() }}
                    </div>
                @else
                <br>
                    <p class="text-center">Нет пользователей в системе</p>
                @endif     
                </div>
        </div>
    </div>
</div>
@endsection

@section('nav-link')
<li class="nav-item menu-logo">
<a class="nav-link"  href="{{ route('admin_panel') }}" role="button">
    <div class="menu-admin-panel" title="Админ. панель"></div>
  </a>
</li>
<li class="nav-item menu-logo">
<a class="nav-link"  href="{{ route('admin_password') }}" role="button">
    <div class="menu-change-password" title="Изменить пароль"></div>
  </a>
</li>
<li class="nav-item menu-logo">
    <div class="menu-space" ></div>
</li>

@endsection

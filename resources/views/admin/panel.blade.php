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
                          <th>Логин</th>
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
                            <form class="" action="#" method="post" onsubmit="if(confirm('Удалить все файлы пользователя?')){return true}else{return false}">
                                <input type="hidden" name="_method" value="DELETE">
                                 {{ csrf_field() }}
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
                                <button type="submit" class="my-submit-btn">
                                  <div class="delete-all-file-admin modile-icons" title="Удалить все файлы пользователя"></div>
                                </button>
                             </form>   
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

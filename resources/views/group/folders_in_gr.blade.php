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
                        {{ $group->title }}
                    </div>
                </div>
                @if ( $public_folders_rez->isNotEmpty()  )
                    <table class="table table-bordered table-sm my-table table-condensed">
                      <thead>
                        <tr>
                          <th>
                            <span class="input-find-caption">Имя</span>
                          </th>
                          <th class="d-none d-md-table-cell">Дата создания</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach ($public_folders_rez as $fl)
                          <tr>
                            <td >
                              <a href="{{ route('child',['folder' => $fl->slug, 'group' => $group->slug]) }}" class="folder-link">  
                                <div class="folder-container" >
                                    <div class="folder-public-root modile-icons" title="Корень монитирования"></div>
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
                          </tr>
                          @endforeach
                        </tbody>
                    </table>
                @else
                <br>
                    <p class="text-center">Группа пуста</p>
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
  <a class="nav-link" href="{{ route('user_group') }}" role="button">
    <div class="menu-grop" title="Группы"></div>
  </a>
</li>
@endsection

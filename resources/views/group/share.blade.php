@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $group->title }}</div>
                <div class="row justify-content-center">
                    <div class="col-8">
                    <br> 
                    <div class="row justify-content-center">
                            <p>Для вступления в группу отправьте ссылку зарегистрированному пользователю</p>
                        </div> 
                     <div class="row justify-content-center">    
                        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                        <script src="//yastatic.net/share2/share.js"></script>
                        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram" data-url="{{ route('group_sub_form',$group) }}" size="m">
                        </div>
                    </div> 
                    <br>
                       <input id="myInput" class="form-control" type="text" value={{ route('group_sub_form',$group) }} />
                        <br>
                        <div class="row justify-content-center">
                            <button id="copy-link" class="btn btn-primary" onclick="myCopyFun()">Копировать</button>
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
  <a class="nav-link" href="{{ route('user_group') }}" role="button">
    <div class="menu-grop" title="Группы"></div>
  </a>
</li>
@endsection
@section('scripts')
    <script>
        function myCopyFun() {
            let copyText = document.getElementById("myInput");
            copyText.select();
            document.execCommand("copy");
            alert('Скопированно');
        }
    </script>
@endsection

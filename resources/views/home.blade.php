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
                    <th>
                         Наименование
                        <a href="{{ route('base_res_create') }}">
                         <div class="myadd" title="Добавить"></div>
                     </a>
                        </th>
                    <th> №</th>
                     <!--<th>Описание</th>
                     <th>Заводской номер</th>
                        <th>Год выпуска</th>
                        -->
                     <th class="d-none d-lg-table-cell">Категория</th>
                    <th>Статус</th>
                    <th>Отдел</th>
                    <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($children_folder as $res)
                <tr>
                    <td>{{ $res->name }}</td>
                    <td>{{ $res->inventory_number }}</td>
                    <!--    <td>{{ $res->descr }}</td>


                     <td>{{ $res->factory_number }}</td>
                     <td>{{ $res->year_of_issue }}</td>

                    <td>{{ \Carbon\Carbon::parse($res->start_up_date)->format('d/m/Y') }} </td>
                    -->
                    <td class="d-none d-lg-table-cell">{{ $res->category }}</td>
                    <td>{{ $res->status }}</td>
                    <td>{{ $res->dept }}</td>
                    <td>
              <a href="{{ route('base_res_info',$res->id) }}">
                <div class="myfullinfo" title="инфо"></div>
              </a>
              <a href="{{ route('base_res_complect',$res->id) }}">
                <div class="myout" title="комплекция"></div>
              </a>
              <a href="{{ route('base_res_find',$res->id) }}">
                <div class="myin" title="вставить"></div>
              </a>
              <a href="{{ route('base_res_edit',$res->id) }}">
                <div class="mycreate" title="изменить"></div>
              </a>
              <a href="{{ route('base_res_sp',$res->id) }}">
                <div class="mydel" title="списать"></div>
              </a>
              <a href="{{ route('base_res_transfer',$res->id) }}">
                <div class="mytransf" title="передать"></div>
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
        $posent =  (Auth::user()->use_size /  Auth::user()->size)*100;
    ?>
            <div class="progress-bar progress-bar-striped  " role="progressbar" 
            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{ $posent }}%" >
            </div>
            
    </div>
    <p class=" my_prosent_label">{{Auth::user()->use_size}}  / {{ Auth::user()->size }} Mb</p>
</div>    
@endsection

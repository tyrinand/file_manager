<template>
  <div>
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <span class="my-table">Пользователь: {{ user }}</span>
        <span class="my-table">Использовано: {{ usize }}
        </span>
      </div>
    </div>
  <div class="row justify-content-center">
    <div class="col-6 col-md-4">
      <br/>
      <table class="table table-bordered table-sm my-table table-condensed">
        <thead>
        </thead>
        <tbody>
          <tr>
            <td>Кол-во папок</td> <td>{{ count_folder }}</td>
          </tr>
          <tr>
            <td>Кол-во файлов</td> <td> {{ count_file }}</td>
          </tr>
        </tbody>
      </table>
      </div>
  </div>
    <div class="row justify-content-center"><!-- кнопка отправки -->
      <div class="col-6 col-md-4">
        <button class="btn btn-block btn-danger"  @click="delete_obj">Удалить объекты</button>
      </div>
    </div><!-- кнопка отправки -->
     
  </div><!-- конец родительского div -->
</template>
<script>
  export default {
    name: "my-delete",
     data() {
           return{
               fileProgress: 0,
           }
       },
       props:['folders', 'files', 'user','usize','count_folder','count_file'],
        methods: {
          delete_obj(){
            if (confirm("Удалить и заблокировать пользователя?"))
            {

            }
            else
                alert("Вы отменили удаление");
          }
        }
  }
</script>   
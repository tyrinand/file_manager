<template>
  <div>
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <span class="my-table">Пользователь: {{ user }}</span>
        <span class="file-title-upload" v-if="(usize/1024) < 1024" > Использовано: {{  (usize/1024).toFixed(2) }}Kb</span>
        <span class="file-title-upload" v-else="(usize/1048576)" > Использовано: {{  (usize/1048576).toFixed(2) }}Mb</span>
      </div>
    </div>
    <template v-if="Progress > 0" > <!-- v-if="Progress > 0" -->
        <div class="row justify-content-center" >
            <div class="col-10">
               <br>
              <div class="progress" style="height: 40px;">
                <div class="progress-bar" role="progressbar" :style="{ width: Progress + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                  Удалено : {{ title  }}
                </div>
              </div>
            </div>
        </div>
      </template>  
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
              Progress: 0,
              allObjts: 0,
              title: "",
              countDelete: 0
           }
       },
       props:['folders', 'files', 'user','usize','count_folder','count_file'],
        methods: {
        async  delete_obj(){
                  this.allObjts = this.count_folder + this.count_file;

                if (confirm("Удалить и заблокировать пользователя?"))
                  {
                    if(this.allObjts > 0) // есть что удалять
                    {
                      await this.blockUser(this.user);

                      for(let item of this.files)
                          await this.deletefile(item); // функция по удалению файлов
                     for(let item of this.folders)
                        await this.deletefolder(item);// функция по удаленю папок
                    }
                    else
                    {
                      alert('Нет объектов для удаления!!!');
                    }
                  }
                else
                  alert("Вы отменили удаление");
          },
          async deletefile(item)
            {
                await axios.delete('/admin_panel/delete_user_file/' + item.slug)
                .then(response =>{
                    if(response.data.result)
                    {
                      this.count_file--;
                      this.usize -= item.size;
                      this.countDelete++; 
                      this.Progress =  Math.round((this.countDelete / this.allObjts) * 100);
                      this.title = this.countDelete + "/" + this.allObjts;
                      console.log(response.data.user);
                    }
                    else {
                      console.log('Error');
                    }
                })
                .catch(error =>{
                    console.log(error);
                })
            },
            async blockUser(login)
            {
            await  axios.delete('/admin_panel/block_user/' +  this.user)
                  .then(response =>{})
                  .catch(error =>{
                    console.log(error);
                  })
            },
            async deletefolder(item)
            {
              await axios.delete('/admin_panel/delete_user_folder/' + item.slug)
                .then(response =>{
                      this.count_folder--;
                      this.countDelete++; 
                      this.Progress =  Math.round((this.countDelete / this.allObjts) * 100);
                      this.title = this.countDelete + "/" + this.allObjts;
                })
                .catch(error =>{
                    console.log(error);
                })
            }
        }
  }
</script>   
<template>
<div>
  <div v-if="(find_btn)">
    <div class="row justify-content-center">
    <div class="col-12 col-md-4">
      <br/>
      <button class="btn btn-primary btn-block" :disabled="public_btn" @click="select_obj">Поиск объектов</button>
    </div>  
    </div> 
  </div>
  <div v-else >
      <div class="row justify-content-center">
        <div class="prerol"></div>
      </div>  
  </div>  
  <div v-if="(find_result)">
    <div class="row justify-content-center">
    <div class="col-12 col-md-4">
      <br/>
      <p class="text-center">Дочерние папки: ({{sub_foldes.length }})</p>
      <button class="btn btn-primary btn-block" @click="public_folders">Произвести публикацию</button>
    </div>  
    </div> 
  </div>
  <template v-if="(public_btn)" > 
        <div class="row justify-content-center" >
            <div class="col-10">
               <br>
              <div class="progress" style="height: 40px;">
                <div class="progress-bar" role="progressbar" :style="{ width: progress + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                   {{ title  }}
                </div>
              </div>
            </div>
        </div>
      </template>
        <template v-if="(finish_public)" > 
            <p class="text-center">Все папки опубликованы</p>
        </template>

</div>    
</template>
<script>
    export default {
        name: "my-sub",
         data() {
           return{
               sub_foldes: [],
               find_btn: true,
               find_result: false,
               progress: 0,
               count_public: 0,
               public_btn: false, // отправлены на публикацию ничего не отображается кроме прогресс бара.
               title: "",
               finish_public: false
           }
       },
       props:['folder', 'group'],
        methods: {
          select_obj()
          {
            this.find_btn = false;
           let form = new FormData(); // создаем форму на js
                form.append('folder',this.folder); // поле с именем image
                 axios.post('/folder/vd_find_folder',form)
                .then(response =>{
                   //console.log(response);
                   this.find_btn = true;
                   this.sub_foldes = response.data;
                   this.find_result = true;
                })
                .catch(error =>{
                    console.log(error);
                })
          },
        async  public_folders()
              {
                  await this.root_mount(this.sub_foldes[0]); // нулевой элемент корень для отображения

                  await this.add_public(this.group); // папка подписана

                  for(let item of this.sub_foldes)
                          await this.sub_user_folders(item); // функция по удалению файлов        
              },
        async root_mount(root_slug)
            {
            await axios.delete('/folder/vd_find_folder/root/' +  root_slug) // маршрут для корня монтирования
                  .then(response =>{
                  })
                  .catch(error =>{
                    console.log(error);
                  })
            },
            async add_public(slug)
            {
            await axios.delete('/add_public/' + slug) // маршрут для корня монтирования
                  .then(response =>{
                  })
                  .catch(error =>{
                    console.log(error);
                  })
            },
          async sub_user_folders(item)
          {
            this.find_result = false;
            this.public_btn = true;
            let form = new FormData(); // создаем форму на js
                form.append('folder',item);
                form.append('group',this.group);
                form.append('root_mount',this.sub_foldes[0]);

                 axios.post('/folder/vd_sub_user/root',form)
                .then(response =>{
                      this.count_public++;
                      this.progress =  Math.round((this.count_public / this.sub_foldes.length) * 100);
                      this.title = this.count_public + "/" + this.sub_foldes.length;
                      if(this.count_public === this.sub_foldes.length)
                          this.finish_public = true;
                })
                .catch(error =>{
                    console.log(error);
                })
          }  
        }
    }
</script>        

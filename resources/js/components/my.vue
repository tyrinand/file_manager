<template>
  <div>
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item menu-logo">
              <a class="nav-link"  v-bind:href="routeroot[0]" role="button" >
                <div class="menu-folder-up" title="Корневой каталог"></div>
              </a>
            </li>
          </ul>
           <!-- progress -->
          <div class="flex-progress">
            <div class="progress" style="height: 35px;">
              <div class="progress-bar progress-bar-striped  " role="progressbar" 
                  aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" :style="{ width: procent + '%'}" >
              </div>
            </div>
            <p class=" my_prosent_label"> {{(usesize/1048576).toFixed(2)}} / {{ routeroot[3] }}Mb</p>
          </div>    
  <!-- progress -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ routeroot[1] }}  <span class="caret"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" v-bind:href="routeroot[2]">
                  Выйти
                </a>
              </div>
              </li>
            </ul>
        </div>
    </div>
  </nav>
  <main class="py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="card">
            <div class="card-header">Загрузка файлов</div>
              <div class="row justify-content-center">
                <div class="col-10">
                  <br>
                  <p v-if="(totalSize/1024) < 1024" class="text-center">Общий размер файлов: {{ formatSize(totalSize/1024) }} Kb</p>
                  <p v-else class="text-center">Общий размер файлов: {{ formatSize(totalSize/1048576) }} Mb</p>
                  <div class="row justify-content-center" v-if="fileProgress > 0">
                    <hr>
                    <div class="col-10">
                      <div class="progress" style="height: 40px;">
                        <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                          {{ fileCurrent  }}%
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row justify-content-around">
                    <input type="file" class="my-file-input" name="image" 
                    multiple="" @change="fileInputChange" /> 
                    <button class="btn btn-primary" @click="uploadfiles">Загрузить все</button>
                  </div>
                  <hr>
                  <br>
                  <div class="row">
                    <div class="col-12 col-md-12 col-lg-6">
                      <h5 class="text-center">Файлы на отправку ({{fileOrder.length }})</h5>
                      <ul class="list-group">
                        <li class="list-group-item" v-for="(file, index) in fileOrder">
                          <div class="file-item">    
                            <span class="file-title-upload" v-if="(file.size/1024) < 1024" >{{ file.name }}:{{  (file.size/1024).toFixed(2) }}Kb</span>
                            <span class="file-title-upload" v-else="(file.size/1048576)" >{{ file.name }}:{{  (file.size/1048576).toFixed(2) }}Mb</span>
                            <div  @click="deleteFile(index)" class="my-upload-delete" ></div>
                          </div>    
                        </li>
                      </ul>    
                      </div>
        <div class="col-12 col-md-12 col-lg-6">
            <h5 class="text-center">Загруженные файлы ({{fileFinish.length }})</h5>
            <ul class="list-group">
                <li class="list-group-item" v-for="file in fileFinish">
                    <span class="file-title-upload" v-if="(file.size/1024) < 1024" >{{ file.name }}:{{  (file.size/1024).toFixed(2) }}Kb</span>
                    <span class="file-title-upload" v-else="(file.size/1048576)" >{{ file.name }}:{{  (file.size/1048576).toFixed(2) }}Mb</span>
                </li>
            </ul> 
        </div>
    </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
   </main> 
 </div>   
</template>
<script>
    export default {
        name: "my-upload",
         data() {
           return{
               fileOrder: [], // файлы на загрузку
               fileFinish: [], // файлы завершенные загрузку
               fileProgress: 0,
               fileCurrent: "", // подпись текущего файла
               totalSize: 0, // размер выбранных файлов
           }
       },
       props:['folder', 'totaluser', 'usesize','routeroot', 'procent'],
        methods: {
          fileInputChange(){ // когда пользователь выбирает файлы
               let files = Array.from(event.target.files);
               this.fileOrder = files.slice();
               this.totalSize = 0; // обнуляем размер
               let tempSize = 0;
               for( let item of files ) // цикл по элементам
               {
                   tempSize += item.size;
               }
            this.totalSize = tempSize;
           },
            deleteFile(index){ // методу удаления из списка на отправку
                this.fileOrder.splice(index, 1);
                 let tempSize = 0;
                for( let item of this.fileOrder ) // цикл по элементам
                {
                   tempSize += item.size;
                }
            this.totalSize = tempSize;
            },
            formatSize(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            async uploadfiles(){
                let files = this.fileOrder.slice();
                let free_size = this.totaluser - this.usesize - this.totalSize; // из общего размера вычитаем занятый и текущий
                if( free_size > 0)
                {
                    for(let item of files)
                        await this.uploadFile(item);
                }
                else
                    alert("Недостаточно места!!!");
            },
            async uploadFile(item)
            {
                let form = new FormData(); // создаем форму на js
                form.append('image',item); // поле с именем image
                let idFolder = this.folder; //id_родительской папки
                form.append('folder',idFolder); // прикрепляем поле с id 

                await axios.post('/file_upload_save',form,{
                    onUploadProgress: (itemUpload) =>{
                        this.fileProgress = Math.round( (itemUpload.loaded/itemUpload.total) * 100);
                        this.fileCurrent = item.name + ' ' + this.fileProgress;
                    }
                })
                .then(response =>{
                    this.fileProgress = 0; 
                    this.fileCurrent = '';
                    this.fileFinish.push(item);
                    this.fileOrder.splice(item, 1);
                   // console.log(response);
                    if(response.status === 201)
                        alert(response.data); // вывести если файл уже существует!!!
                    else
                        {
                         this.usesize += item.size;
                         this.procent = (this.usesize/this.totaluser)*100;
                        }    
                })
                .catch(error =>{
                    console.log(error);
                })
            }
        }
    }
</script>    
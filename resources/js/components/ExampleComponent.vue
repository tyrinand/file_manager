<template>
<div>
<br>
    <p v-if="totalSize < 1024" class="text-center">Общий размер файлов: {{ formatSize(totalSize) }} Kb</p>
     <p v-else class="text-center">Общий размер файлов: {{ formatSize(totalSize/1024) }} Mb</p>

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
        
        <button class="btn btn-primary">Загрузить все</button>
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
</template>

<script>
    export default {
       data() {
           return{
               fileOrder: [], // файлы на загрузку
               fileFinish: [], // файлы завершенные загрузку
               fileProgress: 0,
               fileCurrent: "", // подпись текущего файла
               totalSize: 0, // размер выбранных файлов
           }
       },
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
              tempSize = (tempSize/1024).toFixed(2);
            this.totalSize = tempSize;
           },
            deleteFile(index){ // методу удаления из списка на отправку
                this.fileOrder.splice(index, 1);
                 let tempSize = 0;
                for( let item of this.fileOrder ) // цикл по элементам
                {
                   tempSize += item.size;
                }
                tempSize = (tempSize/1024).toFixed(2);
            this.totalSize = tempSize;
            },
            formatSize(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
       }
    }
</script>

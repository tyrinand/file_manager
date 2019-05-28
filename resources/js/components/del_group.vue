<template>
  <div>

    <div v-if="(find_btn)">
      <div class="row justify-content-center">
        <div class="col-12 col-md-4">
          <br/>
          <button class="btn btn-danger btn-block" :disabled="finish_public" @click="select_obj">Удалить группу</button>
        </div>  
    </div> 
  </div>
  <div v-else >
      <div class="row justify-content-center">
        <div class="prerol"></div>
      </div>  
  </div> 
    <template v-if="(finish_public)" > 
      <br/>
      <p class="text-center">Группа удалена</p>
    </template>

  </div>    
</template>
<script>
    export default {
        name: "my-del-gr",
         data() {
           return{
               find_btn: true,
               finish_public: false
           }
       },
       props:['group'],
        methods: {
          async select_obj()
          {
            this.find_btn = false;
            await axios.delete('/user_group/group_drop/' + this.group)
                .then(response =>{
                    console.log(response);
                    this.find_btn = true;
                    this.finish_public = true;
                })
                .catch(error =>{
                    console.log(error);
                })
          }
        }
    }
</script>        

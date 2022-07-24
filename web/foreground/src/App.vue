<template>
  <Navbar></Navbar>
  <router-view></router-view>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component';
import Navbar from '@/components/Navbar.vue';
import { get_user } from './axios/user';
@Options({
  components: {
    Navbar,
  }
})
export default class App extends Vue { 
  created(){
    get_user((response:any)=>{
      if(response.data.status === "success"){
        this.$store.state.user_id = response.data.result.user_id;
        this.$store.state.username = response.data.result.username;
        this.$store.state.user_email = response.data.result.user_email;
        this.$store.state.user_profile_photo = response.data.result.user_profile_photo;
      }else{
        console.log("token验证失败");
      }
    });
  }
}
</script>

<style lang="scss">
* {
  margin: 0px;
  border: 0px;
  padding: 0px;
}

body {
  font-family: PingFang SC, HarmonyOS_Regular, Helvetica Neue, Microsoft YaHei,
    sans-serif;
    width: 100%;
    height: 100%;
  // font-family: Avenir, Helvetica, Arial, sans-serif;
}

</style>

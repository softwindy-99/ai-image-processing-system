<template>
  <Navbar></Navbar>
  <router-view></router-view>
</template>

<script lang="ts">
import Navbar from '@/components/NavBar.vue';
import { defineComponent } from 'vue';
import { get_user } from './axios/user';
export default defineComponent({
  name: 'App',
  components: {
    Navbar,
  },
  created() {
    const token: unknown = localStorage.getItem('Authorization');
    if (token != null) {
      console.log("App-created(): token exist, try to check token");
      get_user((response: any) => {
        if (response.data.status === "success") { // token 有效
          console.log("get_user(): token ok");
          let arr: [boolean, number, string, string, string];
          if (response.data.result.user_profile_photo != null) {
            arr = [true, response.data.result.user_id, response.data.result.username, response.data.result.user_email, response.data.result.user_profile_photo];
          } else {
            arr = [true, response.data.result.user_id, response.data.result.username, response.data.result.user_email, ""];
          }
          this.$store.commit("settingState", arr);
        } else { // token 无效
          console.log("get_user(): token no");
          this.$store.commit("resettingState");
        }
        this.$store.commit("init"); // 标记初始化已完成
      });
    }
    else {
      console.log("App-created(): token is null");
    }
  }
});
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
}
</style>

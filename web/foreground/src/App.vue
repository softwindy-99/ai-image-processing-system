<template>
  <Navbar></Navbar>
  <router-view></router-view>
</template>

<script lang="ts">
import Navbar from '@/components/NavBar.vue';
import { defineComponent } from 'vue';
import { getUser, putUser } from './axios/user';
export default defineComponent({
  name: 'App',
  components: {
    Navbar,
  },
  methods: {
    test() {
      const token: unknown = localStorage.getItem('Authorization');
      if (token != null) {
        console.log("App-test(): token exist, try to put user");
        putUser("user01", "user01@user.com", (response: any) => { console.log(response); });
      } else {
        console.log("App-test(): token is null");
      }
    }
  },
  created() {
    this.test();
    const token: unknown = localStorage.getItem('Authorization');
    if (token != null) {
      console.log("App-created(): token exist, try to check token");
      getUser((response: any) => {
        if (response.data.status === "success") { // token 有效
          console.log("App-getUser(): token ok");
          let arr: [boolean, number, string, string, string];
          if (response.data.result.user_profile_photo != null) {
            arr = [true, response.data.result.user_id, response.data.result.username, response.data.result.user_email, response.data.result.user_profile_photo];
          } else {
            arr = [true, response.data.result.user_id, response.data.result.username, response.data.result.user_email, ""];
          }
          this.$store.commit("settingState", arr);
        } else { // token 无效
          console.log("App-getUser(): token no");
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
  font-family: 'Helvetica Neue', Helvetica, 'PingFang SC', 'Hiragino Sans GB',
    'Microsoft YaHei', '微软雅黑', Arial, sans-serif;
  width: 100%;
  height: 100%;
}
</style>

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
      console.log("App-created(): token exist");
      get_user(this); // 该实例对象
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

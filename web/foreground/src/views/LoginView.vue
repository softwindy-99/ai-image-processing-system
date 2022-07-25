<template>
  <div class="login">
    <UserLogin v-if="flag === 'login'" @change-flag="change_flag"></UserLogin>
    <UserLogon v-else-if="flag === 'logon'"></UserLogon>
    <div v-else-if="flag === 'logined'"></div>
    <div v-else>错误</div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import UserLogin from '../components/UserLogin.vue';
import UserLogon from '@/components/UserLogon.vue';
export default defineComponent({
  name: 'LoginView',
  data() {
    return {
      flag: "login"
    }
  },
  components: {
    UserLogin,
    UserLogon
  },
  methods: {
    change_flag(value: string) {
      this.flag = value;
    }
  }
});
</script>
<style lang="scss" scoped>
@charset "UTF-8";
$large_width: 100%;

@media (min-width: 1312px) {
  .login {
    width: $large_width;
    height: auto;
    // 通过绝对定位的方式实现占满剩余高度
    position: absolute;
    top: 64px;
    bottom: 0px;
    // 防止挡住 nav 的边框阴影
    z-index: -1;
  }
}
</style>

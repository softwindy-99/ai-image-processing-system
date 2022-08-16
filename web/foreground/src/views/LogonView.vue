<template>
  <div class="login">
    <UserLogon></UserLogon>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import UserLogon from '@/components/UserLogon.vue';
export default defineComponent({
  name: 'LogonView',
  data() {
    return {
    }
  },
  components: {
    UserLogon
  },
  watch: {
    "$store.state.login_flag"(newVal) {
      if (newVal) {
        console.log("LogOnView: user is logined, route to '/usr/home'");
        this.$router.push("/user/home");
      }
    }
  }
});
</script>
<style lang="scss" scoped>
@import "@/scss/index.scss";
@media (min-width: $desktop_width) {
  .login {
    width: 100%;
    height: auto;
    // 通过绝对定位的方式实现占满剩余高度
    position: absolute;
    top: 64px;
    bottom: 0px;
    // 防止挡住 nav 的边框阴影
    z-index: -1;
    background-color: #f4f9ff;
  }
}
</style>

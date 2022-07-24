<template>
  <div class="user">
    <Login v-if="flag === 'login'"></Login>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component';
import Login from '../components/Login.vue';

@Options({
  components: {
    Login
  },
})
export default class HomeView extends Vue {
  flag = "login";
  created() {
    console.log(this.$store);
  }
}
</script>
<style lang="scss" scoped>
@charset "UTF-8";
$large_width: 100%;
@media (min-width: 1312px){
  .user{
    width: $large_width;
    min-height: 600px;
    height: auto;
    // 通过绝对定位的方式实现占满剩余高度
    position: absolute;
    top: 64px;
    bottom: 0px;
  }
}
</style>

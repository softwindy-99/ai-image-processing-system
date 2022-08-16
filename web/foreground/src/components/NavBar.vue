<template>
  <div class="container">
    <nav class="navbar">
      <img class="logo" src="../assets/logo.png" v-on:click="click_to('/')" />
      <ul>
        <li v-for="item in nav_menu" v-bind:key="item.id" v-on:click="click_to(item.link)">{{ item.title }}</li>
      </ul>
      <div class="user">
        <span v-if='username === ""' v-on:click="click_to('/login')">点击登录</span>
        <span v-else v-on:click="click_to('/user/home')">{{ username }}</span>
        <img v-if='user_profile_photo === ""' src="../assets/default_avatar.png" />
        <img v-else v-bind:src="user_profile_photo" />
      </div>
    </nav>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
  name: "NavBar",
  data() {
    return {
      nav_menu: [
        { id: 1, title: "图像修复", link: "/repair" }
      ],
    }
  },
  computed: {
    username(): string {
      return this.$store.state.username;
    },
    user_profile_photo(): string {
      return this.$store.state.user_profile_photo;
    }
  },
  methods: {
    click_to(url :string) {
      this.$router.push(url);
    }
  }
});

</script>

<style style lang="scss" scoped>
@charset "UTF-8";

.navbar {
  width: 80%;
  height: 64px;
  padding: 0 10%;
  box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
  background-color: rgba(255, 255, 255, 1);
}

.logo {
  width: 48px;
  height: 48px;
  margin: 8px 8px;
  display: block;
  float: left;
  cursor: pointer;
}

ul {
  user-select: none;
  float: left;
}

li {
  width: 72px;
  height: 64px;
  margin: 0px 16px;
  font-size: 18px;
  color: #000;
  text-align: center;
  line-height: 64px;
  display: inline-block;
  list-style: none;
}

li:hover {
  color: #336699;
  cursor: pointer;
}

.user {
  width: 160px;
  height: 64px;
  user-select: none;
  float: right;
}

.user span {
  width: 80px;
  height: 64px;
  margin: 0 8px;
  font-size: 14px;
  color: #000;
  text-align: center;
  line-height: 64px;
  letter-spacing: 1px;
  // 超出文字变为省略号 下三行
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  float: left;
  display: block;
  cursor: pointer;
  transition: all 0.2s;
  transition-timing-function: ease-out;
}

.user span:hover {
  letter-spacing: 1.5px;
  font-weight: bold;
}

.user img {
  width: 48px;
  height: 48px;
  margin: 7px 7px;
  border: 1px solid rgba(237, 237, 237, 1);
  border-radius: 5%;
  display: block;
  float: left;
}
</style>
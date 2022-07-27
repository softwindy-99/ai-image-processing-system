<template>
  <div class="container">
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'UserLoginout',
  created(){
    console.log("UserLoginout-created(): remove post_token, route to '/login'");
    localStorage.removeItem("Authorization");
    this.$store.commit("resettingState");
    this.$router.push("/login");
  }
});
</script>

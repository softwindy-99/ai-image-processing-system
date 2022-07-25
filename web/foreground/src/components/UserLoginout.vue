<template>
  <div class="container">
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'UserLoginout',
  created(){
    localStorage.removeItem("Authorization");
    this.$router.push("/login");
  }
});
</script>

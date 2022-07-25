<template>
    <!-- To use
    <UserInput :type="" :title="" ref=""></UserInput>
    -->
    <div class="container">
        <input v-bind:type="type" v-bind:placeholder="title" v-model="value" />
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
    name: "UserInput",
    props: {
        type: String,
        title: String
    },
    data() {
        return {
            value: ""
        }
    }
});
</script>
<style lang="scss" scoped>
input {
    width: 254px;
    height: 22px;
    padding: 8px 8px 8px 8px;
    margin: 8px auto;
    font-size: 14px;
    border: 1px solid #afafaf;
    border-radius: 3px;
    outline: none;
}
</style>

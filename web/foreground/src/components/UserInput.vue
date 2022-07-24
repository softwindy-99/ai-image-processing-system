<template>
<!-- To use
    <UserInput :type="" :title="" ref=""></UserInput>
-->
    <div class="container">
        <input v-bind:type="type" v-bind:placeholder="title" v-model="value" />
    </div>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component';
@Options({
    props: ["type", "title"] // type和title属性不在UserInput上，编辑器会提示无此属性，但编译通过。
})
export default class UserInput extends Vue {
    value = "";
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
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

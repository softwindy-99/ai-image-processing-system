<template>
    <div class="container">
        <div class="login">
            <img src="../assets/logo.png" />
            <div class="user-input">
                <UserInput :type="'text'" :title="'用户名'" ref="username"></UserInput>
                <UserInput :type="'password'" :title="'密码'" ref="password"></UserInput>
                <UserInput :type="'text'" :title="'邮箱'" ref="email"></UserInput>
                <div class="waring-text" v-show="waring_flag">{{ waring_text }}</div>
            </div>
            <div class="user-button">
                <button class="logon-button" v-on:click="click_logon()">注册</button>
                <button class="back-button" v-on:click="click_logon()">返回</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import UserInput from './UserInput.vue';
import { defineComponent } from 'vue';
export default defineComponent({
    name: "UserLogon",
    components: {
        UserInput
    },
    data() {
        return {
            waring_flag: false,
            waring_text: "提示文本"
        }
    },
    methods: {
        click_logon() {
            console.log("UserLogin-click_logon()");
        }
    }
});

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.login {
    width: 272px;
    height: 384px;
    padding: 32px 64px 16px 64px;
    border-radius: 12px;
    margin: 64px auto;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffffff;
}

.login img {
    width: 96px;
    height: 96px;
    display: block;
    margin: 0 auto;
}

.waring-text {
    width: 272px;
    height: 16px;
    margin: 0px auto;
    color: red;
    font-size: 12px;
}

.user-button {
    margin: 24px auto;
}

.logon button {
    width: 270px;
    height: 34px;
    display: block;
    font-size: 18px;
    margin: 8px auto;
    cursor: pointer;
    border-radius: 3px;
    transition: 0.2s all;
}

.logon-button {
    border: 1px solid #336699;
    color: #ffffff;
    background-color: #336699;
}

.login-button:hover {
    color: #336699;
    background-color: #ffffff;
}

.back-button {
    border: 1px solid #99ccff;
    color: #99ccff;
    background-color: #ffffff;
}

.back-button:hover {
    color: #336699;
    border: 1px solid #336699;
}
</style>

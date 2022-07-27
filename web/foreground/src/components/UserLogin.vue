<template>
    <div class="container">
        <div class="login">
            <img src="../assets/logo.png" />
            <div class="user-input">
                <UserInput :type="'text'" :title="'用户名'" ref="username"></UserInput>
                <UserInput :type="'password'" :title="'密码'" ref="password" v-on:keydown.enter="click_login()"></UserInput>
                <div class="waring-text" v-show="waring_flag">{{ waring_text }}</div>
            </div>
            <div class="user-button">
                <button class="login-button" v-on:click="click_login()">登录</button>
                <button class="logon-button" v-on:click="click_logon()">注册</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import UserInput from './UserInput.vue';
import { defineComponent } from 'vue';
import { post_token } from '@/axios/token';
import { get_user } from '@/axios/user';
export default defineComponent({
    name: "UserLogin",
    emits: {
        'change-flag': null,
    },
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
        click_login() {
            console.log("UserLogin-click_login():try to get token");
            const username: any = this.$refs.username;
            const password: any = this.$refs.password;
            post_token(username.value, password.value, (response: any) => {
                if (response.data.status == "success") {
                    console.log("UserLogin-click_login():token success, route to '/user/home'");

                    localStorage.setItem("Authorization", response.data.result.token);

                    this.waring_text = "登录成功，正在跳转...";
                    this.waring_flag = true;
                    get_user((response: any) => {
                        const id: number = (response.data.result.user_id as number);
                        const name: string = (response.data.result.username as string);
                        const email: string = (response.data.result.user_email as string);
                        const profile_photo: any = response.data.result.user_profile;
                        let arr: [boolean, number, string, string, string];
                        if (profile_photo == null) {
                            arr = [true, id, name, email, ""];
                        }
                        else {
                            arr = [true, id, name, email, profile_photo];
                        }
                        this.$store.commit("settingState", arr);
                        this.$router.push("/user/home");
                    });
                    //setTimeout(() => { this.$router.push("/user/home") }, 1000);
                } else {
                    console.log("UserLogin-click_login():token defeat");

                    this.waring_text = response.data.message;
                    this.waring_flag = true;
                }
            });
        },
        click_logon() {
            console.log("UserLogin-click_logon(): route to '/logon'");

            this.$router.push("/logon");
        }
    },
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
    user-select: none;
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

.login button {
    width: 270px;
    height: 34px;
    display: block;
    font-size: 18px;
    margin: 8px auto;
    cursor: pointer;
    border-radius: 3px;
    transition: 0.2s all;
}

.login-button {
    border: 1px solid #336699;
    color: #ffffff;
    background-color: #336699;
}

.login-button:hover {
    color: #336699;
    background-color: #ffffff;
}

.logon-button {
    border: 1px solid #99ccff;
    color: #99ccff;
    background-color: #ffffff;
}

.logon-button:hover {
    color: #336699;
    border: 1px solid #336699;
}
</style>

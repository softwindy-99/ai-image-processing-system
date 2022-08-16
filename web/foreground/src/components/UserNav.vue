<template>
    <div class="container">
        <nav class="navbar">
            <div class="user_avatar">
                <img v-if='user_profile_photo === ""' src="../assets/default_avatar.png" />
                <img v-else v-bind:src="user_profile_photo" />
                <p>{{ username }}</p>
            </div>
            <div class="menu">
                <ul>
                    <li v-for="item in nav_menu" v-bind:key="item.id" v-bind:class="item.class_css"
                        v-on:click="click_to(item.link)">
                        {{ item.title }}
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
export default defineComponent({
    name: "UserNav",
    data() {
        return {
            nav_menu: [
                { id: 1, title: "个人中心", link: "/user/home", class_css: "unselect" },
                { id: 2, title: "密钥设置", link: "/user/key", class_css: "unselect" },
                { id: 3, title: "账号设置", link: "/user/account", class_css: "unselect" },
                { id: 4, title: "退出登录", link: "/user/loginout", class_css: "unselect" }
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
        click_to(url: string) {
            this.$router.push(url);
            this.nav_menu.forEach((item) => {
                if (item.link != url) {
                    item.class_css = "unselect";
                }
                else {
                    item.class_css = "selected";
                }
            });
        }
    },
    watch: {
        '$route.path': function (newVal) {
            const path = newVal;
            this.nav_menu.forEach((item) => {
                if (item.link != path) {
                    item.class_css = "unselect";
                }
                else {
                    item.class_css = "selected";
                }
            });
        }
    },
    created() {
        const path = this.$route.path;
        this.nav_menu.forEach((item) => {
            if (item.link != path) {
                item.class_css = "unselect";
            }
            else {
                item.class_css = "selected";
            }
        });
    }
});

</script>

<style style lang="scss" scoped>
@import "@/scss/index.scss";

@media (min-width: $desktop_width) {
    .container {
        width: 320px;
        height: 100%;
    }

    .navbar {
        width: 100%;
        height: 100%;
        background-color: #336699;
    }

    .user_avatar {
        width: 100%;
        height: auto;
        padding: 32px 0;
    }

    .user_avatar img {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        margin: 0 auto;
        display: block;
        background-color: #fff;
    }

    .user_avatar p {
        font-size: 24px;
        color: #fff;
        text-align: center;
        user-select: none;
    }

    .menu {
        width: 100%;
        height: auto;
    }

    .menu li {
        width: 80%;
        height: auto;
        padding: 10px 0px;
        margin: 8px auto;
        border-radius: 20px;
        font-size: 20px;
        color: #fff;
        text-align: center;
        list-style: none;
        transition: 0.1s all ease;
        cursor: pointer;
    }

    .menu li:hover {
        color: #336699;
        background-color: #ffffff
    }

    .selected {
        color: #336699 !important;
        background-color: #ffffff
    }

    .unselect {
        color: #ffffff;
        background-color: #336699
    }
}
</style>
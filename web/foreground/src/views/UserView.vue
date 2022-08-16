<template>
    <div class="user">
        <div class="content">
            <UserNav></UserNav>
            <router-view></router-view>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import UserNav from '@/components/UserNav.vue';
export default defineComponent({
    name: 'UserView',
    components: {
        UserNav
    },
    watch: {
        "$store.state.login_flag"(newVal) {
            if (!newVal) {
                console.log("LogOnView: user is loginout, route to '/login'");
            }
        }
    }
});
</script>
<style lang="scss" scoped>
@import "@/scss/index.scss";

@media (min-width: $desktop_width) {
    .user {
        width: 100%;
        height: auto;
        // 通过绝对定位的方式实现占满剩余高度
        position: absolute;
        top: 64px;
        bottom: 0px;
        // 防止挡住 nav 的边框阴影
        z-index: -1;
        padding: 32px 0;
        min-height: 560px;
        background-color: #f4f9ff;
    }

    .content {
        width: 95%;
        height: 100%;
        border-radius: 5px;
        margin: 0 auto;
        box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
        background-color: #fff;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: row;
    }

    UserNav {
        float: left;
    }

    router-view {
        float: left;
    }
}
</style>

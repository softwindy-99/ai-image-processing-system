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
    data() {
        return {

        }
    },
    components: {
        UserNav
    },
    methods: {
  
    }
});
</script>
<style lang="scss" scoped>
@charset "UTF-8";
$large_width: 100%;

@media (min-width: 1312px) {
    .user {
        width: $large_width;
        height: auto;
        // 通过绝对定位的方式实现占满剩余高度
        position: absolute;
        top: 64px;
        bottom: 0px;
        // 防止挡住 nav 的边框阴影
        z-index: -1;
        background-color: #f4f9ff;
    }

    .content {
        width: 95%;
        height: 90%;
        min-height: 560px;
        border-radius: 5px;
        margin: 32px auto;
        box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
        background-color: #fff;
        position: relative;
        overflow: hidden;
    }

    UserNav {
        float: left;
    }

    router-view {
        float: left;
    }
}
</style>

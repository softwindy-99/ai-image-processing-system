import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';
import store from '@/store';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import LogonView from "../views/LogonView.vue";
import UserView from "../views/UserView.vue";
import UserHome from "../components/UserHome.vue";
import UserKey from "../components/UserKey.vue";
import UserAccount from "../components/UserAccount.vue";
import UserLoginout from "../components/UserLoginout.vue";
const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/logon',
    name: 'logon',
    component: LogonView
  },
  {
    path: '/user',
    name: 'user',
    component: UserView,
    children: [
      {
        path: "/user/key",
        name: "user_key",
        component: UserKey
      },
      {
        path: "/user/account",
        name: "user_account",
        component: UserAccount
      }
      , {
        path: "/user/loginout",
        name: "user_loginout",
        component: UserLoginout
      },
      {
        path: "/user/home",
        name: "user_home",
        component: UserHome
      }
    ]
  }
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
});
// 前置路由守卫
router.beforeEach(async (to, from) => {
  // 用户未登录
  if (!store.state.login_flag && to.name !== 'login' && to.name !== 'home' && to.name !== 'logon') {
    if (localStorage.getItem('Authorization') == null)
      return { name: 'login' }
  }
  // 用户已登录
  else if (store.state.login_flag && (to.name == 'login' || to.name == 'logon')) {
    return { name: 'user_home' }
  }
})
export default router;

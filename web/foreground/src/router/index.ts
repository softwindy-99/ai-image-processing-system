import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import UserView from "../views/UserView.vue";
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
      ,{
        path: "/user/loginout",
        name: "user_loginout",
        component: UserLoginout
      }
    ]
  }
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
});

export default router;

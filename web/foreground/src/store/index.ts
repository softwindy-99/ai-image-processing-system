import { createStore } from 'vuex'

export default createStore({
  state: {
    init:false,
    login_flag: false,
    user_id: 0,
    username: "",
    user_email: "",
    user_profile_photo: ""
  },
  getters: {
  },
  mutations: {
    init(state){
      state.init = true;
    },
    resettingState(state) {
      state.login_flag = false;
      state.user_id = 0;
      state.username = "";
      state.user_email = "";
      state.user_profile_photo = "";
    },
    settingState(state, arr: [boolean, number, string, string, string]) {
      // 这种方式不够优雅，需要改进
      state.login_flag = arr[0];
      state.user_id = arr[1];
      state.username = arr[2];
      state.user_email = arr[3];
      state.user_profile_photo = arr[4];
    }
  },
  actions: {
  },
  modules: {
  }
})

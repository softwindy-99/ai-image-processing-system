import { ComponentCustomProperties } from 'vue'
import { Store } from 'vuex'
declare module '@vue/runtime-core' {
  // declare your own store states
  interface State {
    init:boolean,
    login_flag: boolean,
    user_id: number,
    username: string,
    user_email: string,
    user_profile_photo: string
  }
  // provide typings for `this.$store`
  interface ComponentCustomProperties {
    $store: Store<State>
  }
}
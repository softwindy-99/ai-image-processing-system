// vuex.d.ts
// 记录：如果没有“vuex.d.ts”配置文件，实例就没有$store属性
import { ComponentCustomProperties } from 'vue'
import { Store } from 'vuex'
declare module '@vue/runtime-core' {
  // declare your own store states
  /*interface State {
    count: number
  }*/
  // provide typings for `this.$store`
  interface ComponentCustomProperties {
    $store: Store<State>
  }
}
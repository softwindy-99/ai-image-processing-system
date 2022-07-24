import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            user_id: 0,
            username: "",
            user_profile_photo: ""
        }
    },
    mutations: {
        user(state: any, user: any) {
            state.user_id = user.id;
            state.username = user.name;
            state.user_email = user.user_email;
            state.user_profile_photo = user.user_profile_photo;
        },
        Text(state: any){
            state.username = "please login";
        }
    }
});

export default store;
import Axios from "./index";

function get_user(
    vue: { $store: { state: { username: string; user_id: number; user_email: string; user_profile_photo: string; }; }; }
): void {
    Axios({
        url: "/user",
        method: "get",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
        .then(
            (response) => {
                if (response.data.status === "success") {
                    console.log("get_user(): token ok");
                    vue.$store.state.username = response.data.result.username;
                    if (response.data.result.user_id != null)
                        vue.$store.state.user_id = response.data.result.user_id;
                    vue.$store.state.user_email = response.data.result.user_email;
                    if (response.data.result.user_profile_photo != null)
                        vue.$store.state.user_profile_photo = response.data.result.user_profile_photo;
                } else {
                    console.log("get_user(): token no");
                }
            })
        .catch(
            (reason) => {
                console.log(reason)
            });
}

export {
    get_user
}
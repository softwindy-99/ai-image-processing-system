import Axios from "./index";
import store from "@/store";
function post_token(username: string, password: string, callback: any) {
    const params: FormData = new FormData();
    params.append("username", username);
    params.append("password", password);
    Axios({
        url: "/token",
        method: "post",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: params // {}的方式传不出去
    })
        .then(
            (response) => {
                callback(response);
            })
        .catch(
            (reason) => {
                console.log(reason)
            });
}

export {
    post_token
}
import Axios from "./index";

function post_token(username: string, password: string, callback: any) {
    Axios({
        url: "/token",
        method: "post",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: {
            username: username,
            password: password
        }
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
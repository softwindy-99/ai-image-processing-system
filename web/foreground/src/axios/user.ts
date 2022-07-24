import Axios from "./index";

function get_user(callback: any) {
    Axios({
        url: "/user",
        method: "post",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
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
    get_user
}
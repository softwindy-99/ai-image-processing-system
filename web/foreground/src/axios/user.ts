import Axios from "./index";
function get_user(callback: any): void {
    Axios({
        url: "/user",
        method: "get",
    })
        .then(
            (response) => { callback(response); }
        )
        .catch(
            (reason) => { console.log(reason) }
        );
}
function post_user(username: string, password: string, email: string, callback: any): void {
    const params: FormData = new FormData();
    params.append("username", username);
    params.append("password", password);
    params.append("email", email);
    Axios({
        url: "/user",
        method: "post",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: params
    })
        .then((response) => { callback(response); }
        )
        .catch((reason) => { console.log(reason); }
        )
}
export {
    get_user,
    post_user
}
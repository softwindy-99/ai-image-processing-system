import Axios from "./index";
function getUser(callback: any): void {
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
function postUser(username: string, password: string, email: string, callback: any): void {
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
function putUser(password: string, email: string, callback: any): void {
    const data = { password: password, email: email };
    console.log(JSON.stringify(data));
    Axios({
        url: "/user",
        method: "put",
        headers: { 'Content-Type': 'application/json' },
        data: JSON.stringify(data)
    })
        .then((response) => { callback(response); }
        )
        .catch((reason) => { console.log(reason); }
        )
}
export {
    getUser,
    postUser,
    putUser
}
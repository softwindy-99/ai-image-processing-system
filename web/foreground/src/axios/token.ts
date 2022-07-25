import Axios from "./index";

function post_token(username: string, password: string) {
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
                console.log(response);
                if (response.data.status === "success")
                    localStorage.setItem("Authorization", response.data.result.token);
            })
        .catch(
            (reason) => {
                console.log(reason)
            });
}

export {
    post_token
}
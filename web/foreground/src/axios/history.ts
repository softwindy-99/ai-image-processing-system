import Axios from "./index";

function get_history(callback: any) {
    Axios({
        url: "/history",
        method: "get",
    })
        .then(
            (response) => { callback(response); }
        )
        .catch(
            (reason) => { console.log(reason) }
        );
}

export{
    get_history
}
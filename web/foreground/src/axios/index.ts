import axios, { AxiosRequestConfig } from "axios";

const Axios = axios.create(
    {
        baseURL: "http://localhost:8080",
        timeout: 1000,
    }
);

// 请求拦截器
Axios.interceptors.request.use(
    (config: AxiosRequestConfig) => {
        const Authorization:unknown = localStorage.getItem('Authorization')
        if (Authorization) {
            if (!config) {
                config = {};
            }
            if (!config.headers) {
                config.headers = {};
            }
            const token:string = <string>Authorization;
            config.headers.Authorization = token;
        }
        return config;
    });

export default Axios;
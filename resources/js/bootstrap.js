// bootstrap.js
import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
}

// Optional: global 419 handling
window.axios.interceptors.response.use(
    (r) => r,
    (error) => {
        if (error?.response?.status === 419) {
            window.location.reload();
        }
        return Promise.reject(error);
    },
);

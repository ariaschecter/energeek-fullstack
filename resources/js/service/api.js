import axios from "axios";

const api = axios.create({
    baseURL: "/api", // Set your base API URL
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: true, // If you are dealing with cookies/session
});

export default api;

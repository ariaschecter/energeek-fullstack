import api from "./api";

export function createUserAndTask(data) {
    return api.post("/v1/home", data);
}

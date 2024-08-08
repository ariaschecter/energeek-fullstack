import api from "./api";

export function createUserAndTask(data) {
    return api.post("/v1/home", data);
}

export function getCategories(data) {
    return api.get("/v1/categories", data);
}

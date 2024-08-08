import api from "./api";

export function getTasks() {
    return api.get("/v1/tasks");
}

export function getTask(id) {
    return api.get(`/v1/tasks/${id}`);
}

export function createTask(data) {
    return api.post("/v1/tasks", data);
}

export function updateTask(id, data) {
    return api.put(`/v1/tasks/${id}`, data);
}

export function deleteTask(id) {
    return api.delete(`/v1/tasks/${id}`);
}

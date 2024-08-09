<script setup>
import { Head } from "@inertiajs/vue3";
import Logo from "@/assets/energeek-logo.png";
import { onMounted, ref } from "vue";
import { createUserAndTask, getCategories } from "@/service/home";

import Swal from "sweetalert2";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

defineProps({});

const categories = ref(null);
const firstCategory = ref(null);
const name = ref(null);
const username = ref(null);
const email = ref(null);
const todoListDatas = ref([]);

// Fetch data from API
const fetchData = async () => {
    try {
        const response = await getCategories();

        categories.value = response.data.data;

        firstCategory.value = categories.value[0];

        todoListDatas.value.push({
            description: "",
            category_id: firstCategory.value?.id,
        });
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

// reset form to default
const resetForm = () => {
    name.value = null;
    username.value = null;
    email.value = null;
    todoListDatas.value = [
        {
            description: "",
            category_id: firstCategory.value?.id,
        },
    ];
};

// Store data with axios
const storeData = async () => {
    const payload = {
        name: name.value,
        username: username.value,
        email: email.value,
        tasks: todoListDatas.value,
    };

    // Cek apakah deskripsi todo list masih kosong
    const checkIsEmpty = checkIsDescriptionEmpty(payload.tasks);

    if (checkIsEmpty) {
        return toast.error("Isi deskripsi todo list terlebih dahulu");
    }

    // Store data to API with axios
    await createUserAndTask(payload)
        .then((res) => {
            const data = res.data;
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: data.message,
            });
            resetForm();
        })
        .catch((err) => {
            const response = err.response;
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: response?.data.errors.map((error) => error[0]).join(" "),
            });
        });
};

// Cek apakah deskripsi todo list masih kosong
const checkIsDescriptionEmpty = (todoLists) => {
    return todoLists.some((item) => item.description === "");
};

// Menambahkan array todo list baru
const addTodo = () => {
    const checkIsEmpty = checkIsDescriptionEmpty(todoListDatas.value);

    if (!checkIsEmpty) {
        todoListDatas.value.push({
            description: "",
            category_id: firstCategory.value?.id,
        });
    } else {
        return toast.error("Isi deskripsi todo list terlebih dahulu");
    }
};

// Menghapus array todo list pada index
const removeTodo = (index) => {
    if (todoListDatas.value.length > 1) {
        todoListDatas.value.splice(index, 1);
    } else {
        return toast.error("Minimal terdapat 1 todo list");
    }
};

// Fetch data when the component is mounted
onMounted(() => {
    fetchData();
});
</script>

<template>
    <Head title="Home" />

    <div
        class="relative bg-slate-100 sm:flex sm:justify-center sm:items-center selection:bg-red-500 selection:text-white"
    >
        <div
            class="w-[75%] min-h-screen h-full items-center mt-20 rounded-lg p-8"
        >
            <div class="text-center mb-8">
                <img
                    :src="Logo"
                    alt="Energeek Logo"
                    class="mx-auto h-16 mb-4"
                />
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8 bg-white p-6 rounded-lg">
                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                        >Nama</label
                    >
                    <input
                        type="text"
                        id="name"
                        name="name"
                        v-model="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                        placeholder="Nama"
                    />
                </div>
                <div>
                    <label
                        for="username"
                        class="block text-sm font-medium text-gray-700"
                        >Username</label
                    >
                    <input
                        type="text"
                        id="username"
                        name="username"
                        v-model="username"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                        placeholder="Username"
                    />
                </div>
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                        >Email</label
                    >
                    <input
                        type="email"
                        id="email"
                        name="email"
                        v-model="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                        placeholder="Email"
                    />
                </div>
            </div>

            <div class="flex justify-between mb-4">
                <h1 class="flex text-lg font-normal">To Do List</h1>
                <button
                    class="flex bg-[#FFE2E5] text-[#F1416C] font-semibold py-2 px-4 rounded-lg shadow gap-2 items-center"
                    @click="addTodo"
                >
                    <span
                        ><svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="currentColor"
                                d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z"
                            />
                        </svg>
                    </span>
                    Tambah To Do
                </button>
            </div>

            <div class="space-y-7">
                <div
                    v-for="(todo, index) in todoListDatas"
                    class="flex flex-cols items-center space-x-4"
                >
                    <div class="w-[85%]">
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                            >Nama</label
                        >
                        <input
                            type="text"
                            name="description"
                            v-model="todo.description"
                            class="flex-1 w-full border-gray-300 rounded-md shadow-sm mt-2 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="Contoh: Perbaikan api master"
                        />
                    </div>
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                            >Kategori</label
                        >
                        <select
                            class="border-gray-300 rounded-md shadow-sm mt-2 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            v-model="todo.category_id"
                        >
                            <option
                                v-for="category in categories"
                                :value="category?.id"
                            >
                                {{ category?.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <button
                            class="mt-8 text-white bg-[#F1416C] p-1 rounded-lg cursor-pointer"
                            @click="removeTodo(index)"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button
                    class="w-full bg-[#049C4F] text-white font-semibold py-2 rounded-lg shadow hover:bg-green-600"
                    @click="storeData"
                >
                    SIMPAN
                </button>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>

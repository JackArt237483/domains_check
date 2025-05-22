<!-- frontend/src/components/Login.vue -->
<template>
    <div class="max-w-md mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Вход</h1>
        <form @submit.prevent="login">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input v-model="email" id="email" type="email" class="w-full p-2 border rounded-md" />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Пароль</label>
                <input v-model="password" id="password" type="password" class="w-full p-2 border rounded-md" />
            </div>
            <button
                type="submit"
                :disabled="isLoading"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:bg-blue-300"
            >
                {{ isLoading ? 'Вход...' : 'Войти' }}
            </button>
        </form>
        <div v-if="error" class="mt-4 text-red-500">{{ error }}</div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            error: '',
            isLoading: false,
            backendUrl: 'http://localhost:8000',
        };
    },
    methods: {
        async login() {
            this.isLoading = true;
            this.error = '';

            try {
                await axios.get(`${this.backendUrl}/sanctum/csrf-cookie`, { withCredentials: true });
                const response = await axios.post(`${this.backendUrl}/api/login`, {
                    email: this.email,
                    password: this.password,
                });
                localStorage.setItem('auth_token', response.data.token);
                this.$emit('login-success');
            } catch (error) {
                this.error = error.response?.data?.error || 'Ошибка входа';
            } finally {
                this.isLoading = false;
            }
        },
    },
};
</script>
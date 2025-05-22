<!-- frontend/src/components/DomainChecker.vue -->
<template>
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Проверка доступности доменов</h1>

        <div v-if="!isAuthenticated" class="text-red-500 mb-4">
            <p>Введите email и пароль:</p>
            <input v-model="email" placeholder="Email" class="border p-2 w-full mb-2" />
            <input v-model="password" type="password" placeholder="Пароль" class="border p-2 w-full mb-2" />
            <button @click="login" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Войти</button>
        </div>

        <form v-else @submit.prevent="checkDomains">
            <div class="mb-4">
                <label for="domains" class="block text-sm font-medium text-gray-700">Домены</label>
                <textarea
                    v-model="domains"
                    id="domains"
                    class="w-full p-2 border rounded-md focus:ring focus:ring-blue-200"
                    rows="5"
                    placeholder="example.com, test.org"
                ></textarea>
            </div>

            <button
                type="submit"
                :disabled="isLoading"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:bg-blue-300"
            >
                {{ isLoading ? 'Проверка...' : 'Проверить' }}
            </button>
        </form>

        <div v-if="error" class="mt-4 text-red-500">{{ error }}</div>

        <div v-if="isLoading" class="flex justify-center mt-4">
            <div class="animate-spin h-5 w-5 border-4 border-blue-500 border-t-transparent rounded-full"></div>
        </div>

        <div v-if="results.length" class="mt-6">
            <h2 class="text-lg font-semibold">Результаты</h2>
            <table class="w-full mt-2 border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left">Домен</th>
                        <th class="p-2 text-left">Статус</th>
                        <th class="p-2 text-left">Истекает</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="result in results" :key="result.domain">
                        <td class="p-2">{{ result.domain }}</td>
                        <td class="p-2">
                            <span :class="result.is_available ? 'text-green-500' : 'text-red-500'">
                                {{ result.is_available ? 'Доступен' : 'Занят' }}
                            </span>
                        </td>
                        <td class="p-2">{{ result.expires_at || '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            domains: '',
            results: [],
            error: '',
            isLoading: false,
            isAuthenticated: false,
            backendUrl: 'http://localhost:8000',
            token: localStorage.getItem('auth_token') || null,
            email: '',
            password: '',
        };
    },
    async created() {
        if (this.token) {
            await this.checkAuth();
        }
    },
    methods: {
        async checkAuth() {
            try {
                const response = await axios.get(`${this.backendUrl}/api/user`, {
                    headers: { Authorization: `Bearer ${this.token}` },
                    withCredentials: true,
                });
                this.isAuthenticated = !!response.data;
            } catch (error) {
                this.token = null;
                localStorage.removeItem('auth_token');
                this.isAuthenticated = false;
            }
        },

        async login() {
            try {
                await axios.get(`${this.backendUrl}/sanctum/csrf-cookie`, { withCredentials: true });
                const response = await axios.post(
                    `${this.backendUrl}/api/login`,
                    { email: this.email, password: this.password },
                    { withCredentials: true }
                );
                this.token = response.data.token;
                localStorage.setItem('auth_token', this.token);
                this.isAuthenticated = true;
                await this.checkAuth();
            } catch (err) {
                this.error = 'Ошибка входа: ' + (err.response?.data?.error || err.message);
            }
        },

        async checkDomains() {
            if (!this.domains.trim()) {
                this.error = 'Введите хотя бы один домен';
                return;
            }

            this.isLoading = true;
            this.error = '';
            this.results = [];

            try {
                const response = await axios.post(
                    `${this.backendUrl}/api/domains/check`,
                    { domains: this.domains },
                    {
                        headers: { Authorization: `Bearer ${this.token}` },
                        withCredentials: true,
                    }
                );
                this.results = response.data;
            } catch (error) {
                this.error = error.response?.data?.error || 'Произошла ошибка при проверке доменов';
            } finally {
                this.isLoading = false;
            }
        },
    },
};
</script>

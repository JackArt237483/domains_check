<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl p-8 space-y-8">
      <h1 class="text-3xl font-extrabold text-center text-gray-800">🔍 Проверка доступности доменов</h1>

      <!-- AUTH -->
      <div v-if="!isAuthenticated" class="space-y-6">
        <p class="text-center text-red-500 font-medium">Введите email и пароль для входа</p>
        <input
          v-model="email"
          placeholder="Email"
          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition"
        />
        <input
          v-model="password"
          type="password"
          placeholder="Пароль"
          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition"
        />
        <button
          @click="login"
          class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-bold transition"
        >
          Войти
        </button>
      </div>

      <!-- DOMAIN FORM -->
      <form v-else @submit.prevent="checkDomains" class="space-y-6">
        <label class="block text-gray-700 font-semibold">
          Введите список доменов:
        </label>
        <textarea
          v-model="domains"
          rows="4"
          placeholder="example.com, test.org"
          class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none resize-none transition"
        ></textarea>
        <button
          type="submit"
          :disabled="isLoading"
          class="w-full bg-blue-500 hover:bg-blue-600 disabled:opacity-50 text-white py-3 rounded-xl font-bold transition"
        >
          {{ isLoading ? '⏳ Проверка...' : '🚀 Проверить домены' }}
        </button>
      </form>

      <!-- ERROR -->
      <div v-if="error" class="text-center text-red-600 font-semibold">
        {{ error }}
      </div>

      <!-- LOADING SPINNER -->
      <div v-if="isLoading" class="flex justify-center mt-6">
        <div class="h-8 w-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
      </div>

      <!-- RESULTS -->
      <div v-if="results.length" class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📋 Результаты:</h2>
        <div class="overflow-auto rounded-xl border border-gray-200">
          <table class="w-full table-auto">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
              <tr>
                <th class="px-6 py-3 text-left">Домен</th>
                <th class="px-6 py-3 text-left">Статус</th>
                <th class="px-6 py-3 text-left">Истекает</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-gray-800">
              <tr v-for="result in results" :key="result.domain" class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 font-medium">{{ result.domain }}</td>
                <td class="px-6 py-3">
                  <span
                    :class="result.is_available ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'"
                  >
                    {{ result.is_available ? '✅ Доступен' : '❌ Занят' }}
                  </span>
                </td>
                <td class="px-6 py-3">{{ result.expires_at || '—' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
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

<!-- frontend/src/App.vue -->
<template>
  <Login v-if="!isAuthenticated" @login-success="checkAuth" />
  <DomainChecker v-else />
</template>

<script>
import DomainChecker from './components/DomainChecker.vue';
import Login from './components/Login.vue';
import axios from 'axios';

export default {
  components: { DomainChecker, Login },
  data() {
      return {
          isAuthenticated: false,
          backendUrl: 'http://localhost:8000',
      };
  },
  async created() {
      await this.checkAuth();
  },
  methods: {
      async checkAuth() {
          try {
              await axios.get(`${this.backendUrl}/sanctum/csrf-cookie`, { withCredentials: true });
              const response = await axios.get(`${this.backendUrl}/api/user`, {
                  headers: { Authorization: `Bearer ${localStorage.getItem('auth_token')}` },
              });
              this.isAuthenticated = !!response.data;
          } catch (error) {
              this.isAuthenticated = false;
          }
      },
  },
};
</script>
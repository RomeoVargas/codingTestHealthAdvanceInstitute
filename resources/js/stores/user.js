import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
        token: null
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        loggedInUser: (state) => state.user
    },
    actions: {
        setUserData(userData, token) {
            this.user = userData;
            this.token = token;

            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('user', JSON.stringify(userData));
            localStorage.setItem('token', token);
        },
        clearUserData() {
            this.user = null;
            this.token = null;

            axios.defaults.headers.common['Authorization'] = '';
            localStorage.removeItem('user');
            localStorage.removeItem('token');
        },
        loadUserFromStorage() {
            const user = localStorage.getItem('user');
            const token = localStorage.getItem('token');
            if (user && token) {
                this.user = JSON.parse(user);
                this.token = token;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            }
        }
    }
});

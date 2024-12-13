<script>
import {useUserStore} from "@/stores/user.js";

export default {
    name: "LoginForm",
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        submit() {
            let me = this,
                loader = me.$loading.show({
                    // The loader covers the full page
                    container: null
                });

            axios.post('/login', {
                email: me.email,
                password: me.password
            }).then((response) => {
                const responseData = response.data,
                    userStore = useUserStore();

                loader.hide();
                userStore.setUserData(responseData.user, responseData.token);
                me.$emit('success', userStore.loggedInUser);
            }).catch((reason) => {
                loader.hide();
                const response = reason.response;
                alert(response.data.message);
            });
        }
    }
}
</script>

<template>
    <main class="form-signin w-100 m-auto">
        <form @submit.prevent="submit">
            <h1 class="h3 mb-3 fw-normal">User Sign-in</h1>

            <div class="form-floating my-3">
                <input type="email" class="form-control" v-model="email" required>
                <label>Email address</label>
            </div>
            <div class="form-floating my-3">
                <input type="password" class="form-control" v-model="password" required>
                <label>Password</label>
            </div>
            <button class="btn btn-primary w-100 my-3 py-2" type="submit">Sign in</button>
        </form>
    </main>
</template>

<style scoped>

</style>

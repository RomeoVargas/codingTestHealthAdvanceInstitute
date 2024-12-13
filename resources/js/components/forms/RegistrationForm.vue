<script>
export default {
    name: "RegistrationForm",
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        }
    },
    methods: {
        submit() {
            let me = this,
                loader = me.$loading.show({
                    container: null
                });

            axios.post('/register', {
                name: me.name,
                email: me.email,
                password: me.password,
                password_confirmation: me.password_confirmation
            }).then((response) => {
                loader.hide();
                localStorage.setItem('token', response.data.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
                me.$emit('success');
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
    <main class="w-100 m-auto">
        <form @submit.prevent="submit">
            <h1 class="h3 mb-3 fw-normal">User Registration</h1>

            <div class="form-floating my-3">
                <input type="text" class="form-control" v-model="name">
                <label>Full Name</label>
            </div>
            <div class="form-floating my-3">
                <input type="email" class="form-control" v-model="email">
                <label>Email address</label>
            </div>
            <div class="form-floating my-3">
                <input type="password" class="form-control" v-model="password">
                <label>Password</label>
            </div>
            <div class="form-floating my-3">
                <input type="password" class="form-control" v-model="password_confirmation">
                <label>Confirm Password</label>
            </div>
            <button class="btn btn-primary w-100 my-3 py-2" type="submit">Submit</button>
        </form>
    </main>
</template>

<style scoped>

</style>

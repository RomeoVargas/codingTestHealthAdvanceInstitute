<script>
import LoginForm from "@/components/forms/LoginForm.vue";

export default {
    name: "LoginPage",
    components: {LoginForm},
    methods: {
        redirectToHomePage(loggedInUser) {
            alert('You are now logged in. Welcome ' + loggedInUser.name + '.');
            this.$router.push('/home');
        }
    }
}
</script>

<template>
    <div>
        <LoginForm @success="redirectToHomePage" />
        <hr />
        <div>
            <small>Don't have an account yet? Register
                <a href="" @click.prevent="$router.push('/register')">here</a>
            </small>
        </div>
    </div>
</template>

<style scoped>

</style>

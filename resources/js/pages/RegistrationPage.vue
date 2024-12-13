<script>
import RegistrationForm from "@/components/forms/RegistrationForm.vue";

export default {
    name: "RegistrationPage",
    components: {RegistrationForm},
    methods: {
        redirectToLogin() {
            alert('Registration successful. You can now log in to your account.');
            this.$router.push('/');
        }
    }
}
</script>

<template>
    <div>
        <RegistrationForm @success="redirectToLogin" />
    </div>
</template>

<style scoped>

</style>

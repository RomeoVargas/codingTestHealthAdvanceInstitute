<template>
    <div>
        <h2>{{ message }}</h2>
    </div>
</template>

<script>
    export default {
        name: 'HomePage',
        data() {
            return {
                message: ''
            }
        },
        mounted() {
            axios.get('/api/home')
                .then(response => {
                    this.message = response.data.message;
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
</script>

<style scoped>
/* Add styles here */
</style>

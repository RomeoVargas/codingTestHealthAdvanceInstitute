<script>
    import {useUserStore} from "@/stores/user.js";

    export default {
        name: 'App',
        data() {
            return {
                loader: null
            };
        },
        created() {
            const userStore = useUserStore();
            userStore.loadUserFromStorage();
        }
    }
</script>

<template>
    <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
            <component :is="Component" />
        </transition>
    </router-view>
</template>

<style scoped>
</style>

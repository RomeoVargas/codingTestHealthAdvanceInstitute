import LoginPage from "@/pages/LoginPage.vue";
import HomePage from "@/pages/HomePage.vue";
import RegistrationPage from "@/pages/RegistrationPage.vue";

const routes = [
    {
        path: '/',
        component: LoginPage,
    },
    {
        path: '/register',
        component: RegistrationPage,
    },
    {
        path: '/home',
        component: HomePage,
    },
];

export default routes;

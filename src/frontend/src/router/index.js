import { createRouter, createWebHistory } from "vue-router";
import { lang } from '../languageImporter';
import HomeView from "../views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: lang('homepage'),
      component: HomeView,
    },
    {
      path: "/about",
      name: lang('aboutpage'),
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import("../views/AboutView.vue"),
    },
    {
      path: "/signup",
      name: "signup",
      component: () => import("../views/SignupView.vue"),
    },
    {
      path: "/login",
      name: "login",
      component: () => import("../views/LoginView.vue"),
    },
    {
      path: "/teszt",
      name: "teszt",
      component: () => import("../views/TesztView.vue"),
    },
  ],
});

export default router;

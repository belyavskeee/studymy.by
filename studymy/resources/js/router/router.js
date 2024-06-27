import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import store from '../store/store';  // Подключаем хранилище Vuex

const router = createRouter({
  history: createWebHistory(),
  linkActiveClass: 'active-link',
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters.isAuthenticated;
  const userPermission = store.getters.userPermission;

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next('/');
      return;
    }

    if (to.matched.some(record => record.meta.allowedRoles)) {
      const allowedRoles = to.meta.allowedRoles;

      if (allowedRoles && !allowedRoles.includes(userPermission)) {
        next('/main');
        return;
      }
    }
  }

  next();
});

export default router;
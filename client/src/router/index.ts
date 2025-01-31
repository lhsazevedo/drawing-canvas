import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import CreateView from '@/views/CreateView.vue'
import CanvasView from '@/views/CanvasView.vue'
import Login from '@/views/auth/Login.vue'
import Signup from '@/views/auth/Signup.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => Login,
    },
    {
      path: '/signup',
      name: 'signup',
      component: () => Signup,
    },
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/create',
      name: 'create',
      component: CreateView,
    },
    {
      path: '/canvas/:id',
      name: 'canvas',
      component: CanvasView,
    },
  ],
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (auth.isAuthenticated && (to.name === 'login' || to.name === 'signup')) {
    return { name: 'home' }
  }
})

export default router

import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import AppLayout from '../layouts/AppLayout.vue'
import LoginView from '../views/LoginView.vue'
import DashboardView from '../views/DashboardView.vue'
import ProductsView from '../views/ProductsView.vue'
import CashierView from '../views/CashierView.vue'
import TransactionsView from '../views/TransactionsView.vue'
import SchedulesView from '../views/SchedulesView.vue'
import AttendanceView from '../views/AttendanceView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', name: 'login', component: LoginView },
    {
      path: '/',
      component: AppLayout,
      meta: { requiresAuth: true },
      children: [
        { path: '', name: 'dashboard', component: DashboardView },
        { path: 'products', name: 'products', component: ProductsView, meta: { role: 'admin' } },
        { path: 'cashier', name: 'cashier', component: CashierView, meta: { role: 'piket' } },
        { path: 'transactions', name: 'transactions', component: TransactionsView },
        { path: 'schedules', name: 'schedules', component: SchedulesView },
        { path: 'attendance', name: 'attendance', component: AttendanceView },
      ],
    },
  ],
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login' }
  }

  if (to.name === 'login' && auth.isAuthenticated) {
    return { name: 'dashboard' }
  }

  if (to.meta.role && auth.user?.role !== to.meta.role) {
    return { name: 'dashboard' }
  }

  return true
})

export default router

<script setup>
import { computed } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

const navItems = computed(() => {
  if (auth.isAdmin) {
    return [
      { to: '/', label: 'Dashboard' },
      { to: '/products', label: 'Products' },
      { to: '/transactions', label: 'Transactions' },
      { to: '/schedules', label: 'Schedules' },
      { to: '/attendance', label: 'Attendance' },
    ]
  }

  return [
    { to: '/', label: 'Dashboard' },
    { to: '/cashier', label: 'Cashier' },
    { to: '/schedules', label: 'Schedule' },
    { to: '/attendance', label: 'Attendance' },
    { to: '/transactions', label: 'History' },
  ]
})

async function logout() {
  await auth.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto grid min-h-screen max-w-[1440px] grid-cols-[260px_1fr] gap-6 p-6 max-lg:grid-cols-1">
      <aside class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-200 pb-5">
          <div class="grid h-11 w-11 place-items-center rounded-xl bg-blue-600 font-bold text-white">P</div>
          <div>
            <p class="font-bold leading-tight">POTI System</p>
            <p class="text-sm text-slate-500">Pojok TI HMJ TI</p>
          </div>
        </div>

        <nav class="mt-6 grid gap-1">
          <RouterLink
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-blue-50 hover:text-blue-700"
            active-class="bg-blue-50 text-blue-700"
          >
            {{ item.label }}
          </RouterLink>
        </nav>

        <div class="mt-auto border-t border-slate-200 pt-5">
          <p class="text-sm font-semibold">{{ auth.user?.name }}</p>
          <p class="mb-4 text-sm capitalize text-slate-500">{{ auth.user?.role }}</p>
          <button class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" type="button" @click="logout">
            Keluar
          </button>
        </div>
      </aside>

      <main class="min-w-0">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const form = reactive({
  login: 'admin@poti.local',
  password: 'password',
})

async function submit() {
  await auth.login(form)
  router.push({ name: 'dashboard' })
}
</script>

<template>
  <main class="grid min-h-screen place-items-center bg-slate-50 p-6">
    <section class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
      <div class="mb-7">
        <div class="mb-4 grid h-12 w-12 place-items-center rounded-xl bg-blue-600 font-bold text-white">P</div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">POTI System</p>
        <h1 class="mt-2 text-2xl font-bold">Masuk ke Operasional POTI</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan akun admin atau piket untuk mengakses dashboard dan kasir.</p>
      </div>

      <form class="grid gap-4" @submit.prevent="submit">
        <label class="grid gap-2 text-sm font-medium">
          Email atau username
          <input v-model="form.login" class="rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-blue-600" placeholder="admin@poti.local" required />
        </label>

        <label class="grid gap-2 text-sm font-medium">
          Password
          <input v-model="form.password" class="rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-blue-600" placeholder="password" type="password" required />
        </label>

        <p v-if="auth.error" class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">{{ auth.error }}</p>

        <button class="rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white hover:bg-blue-700 disabled:opacity-70" type="submit" :disabled="auth.loading">
          {{ auth.loading ? 'Memproses...' : 'Masuk' }}
        </button>
      </form>
    </section>
  </main>
</template>

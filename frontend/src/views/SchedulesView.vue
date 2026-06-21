<script setup>
import { onMounted, reactive, ref } from 'vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import { api } from '../services/api'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const schedules = ref([])
const users = ref([])
const form = reactive({
  user_id: '',
  date: new Date().toISOString().slice(0, 10),
  shift: 'Pagi',
})

async function loadSchedules() {
  const { data } = await api.get('/schedules', { params: { per_page: 50 } })
  schedules.value = data.data
}

async function loadUsersFromSchedules() {
  const { data } = await api.get('/users', { params: { role: 'piket' } })
  users.value = data
  if (!form.user_id && users.value.length > 0) form.user_id = users.value[0].id
}

async function saveSchedule() {
  await api.post('/schedules', form)
  await loadSchedules()
}

async function deleteSchedule(schedule) {
  await api.delete(`/schedules/${schedule.id}`)
  await loadSchedules()
}

onMounted(async () => {
  await loadSchedules()
  if (auth.isAdmin) await loadUsersFromSchedules()
})
</script>

<template>
  <PageHeader title="Schedules" subtitle="Kelola dan lihat jadwal piket POTI." />

  <section class="grid gap-6" :class="auth.isAdmin ? 'xl:grid-cols-[360px_1fr]' : ''">
    <form v-if="auth.isAdmin" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" @submit.prevent="saveSchedule">
      <h2 class="mb-4 font-bold">Tambah Jadwal</h2>
      <div class="grid gap-4">
        <label class="grid gap-2 text-sm font-medium">
          Petugas
          <select v-model="form.user_id" class="rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-600" required>
            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
          </select>
        </label>
        <label class="grid gap-2 text-sm font-medium">
          Tanggal
          <input v-model="form.date" class="rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-600" type="date" required />
        </label>
        <label class="grid gap-2 text-sm font-medium">
          Shift
          <input v-model="form.shift" class="rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-600" placeholder="Pagi" required />
        </label>
        <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" type="submit">Simpan Jadwal</button>
      </div>
    </form>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <EmptyState v-if="schedules.length === 0" title="Belum ada jadwal." message="Jadwal piket akan tampil setelah dibuat oleh admin." />
      <div v-else class="grid gap-3 md:grid-cols-2">
        <article v-for="schedule in schedules" :key="schedule.id" class="rounded-xl border border-slate-200 p-4">
          <p class="text-sm text-slate-500">{{ schedule.date }}</p>
          <strong class="mt-1 block">{{ schedule.user?.name }}</strong>
          <div class="mt-3 flex items-center justify-between gap-3">
            <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700">{{ schedule.shift }}</span>
            <button v-if="auth.isAdmin" class="rounded-lg border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-50" type="button" @click="deleteSchedule(schedule)">Hapus</button>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

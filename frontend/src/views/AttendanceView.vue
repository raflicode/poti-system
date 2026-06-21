<script setup>
import { computed, onMounted, ref } from 'vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import { api } from '../services/api'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const attendances = ref([])
const message = ref('')

const activeAttendance = computed(() => attendances.value.find((attendance) => !attendance.check_out))

async function loadAttendances() {
  const { data } = await api.get('/attendances', { params: { per_page: 50 } })
  attendances.value = data.data
}

async function checkIn() {
  await api.post('/attendances/check-in')
  message.value = 'Check in berhasil.'
  await loadAttendances()
}

async function checkOut() {
  await api.post('/attendances/check-out')
  message.value = 'Check out berhasil.'
  await loadAttendances()
}

onMounted(loadAttendances)
</script>

<template>
  <PageHeader title="Attendance" subtitle="Absensi piket dan riwayat kehadiran." />

  <p v-if="message" class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ message }}</p>

  <section class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h2 class="font-bold">Absensi Hari Ini</h2>
        <p class="mt-1 text-sm text-slate-500">{{ activeAttendance ? 'Sesi check in sedang aktif.' : 'Tidak ada sesi check in aktif.' }}</p>
      </div>
      <div v-if="auth.isPiket || auth.isAdmin" class="flex gap-2">
        <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700 disabled:opacity-50" type="button" :disabled="Boolean(activeAttendance)" @click="checkIn">Check In</button>
        <button class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50" type="button" :disabled="!activeAttendance" @click="checkOut">Check Out</button>
      </div>
    </div>
  </section>

  <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <EmptyState v-if="attendances.length === 0" title="Belum ada absensi." message="Riwayat check in dan check out akan tampil di sini." />
    <div v-else class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="border-b border-slate-200 text-slate-500">
          <tr>
            <th class="py-3">Petugas</th>
            <th class="py-3">Check In</th>
            <th class="py-3">Check Out</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="attendance in attendances" :key="attendance.id">
            <td class="py-3">{{ attendance.user?.name || auth.user?.name }}</td>
            <td class="py-3">{{ new Date(attendance.check_in).toLocaleString('id-ID') }}</td>
            <td class="py-3">{{ attendance.check_out ? new Date(attendance.check_out).toLocaleString('id-ID') : '-' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

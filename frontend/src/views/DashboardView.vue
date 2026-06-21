<script setup>
import { onMounted, ref } from 'vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import { api } from '../services/api'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const loading = ref(true)
const stats = ref({
  daily_revenue: 0,
  total_transactions: 0,
  product_count: 0,
  low_stock_count: 0,
  today_schedule: [],
})

const formatter = new Intl.NumberFormat('id-ID')

onMounted(async () => {
  const { data } = await api.get('/dashboard')
  stats.value = data
  loading.value = false
})
</script>

<template>
  <PageHeader title="Dashboard" :subtitle="auth.isAdmin ? 'Ringkasan operasional hari ini.' : 'Ringkasan shift dan transaksi hari ini.'" />

  <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <p class="text-sm text-slate-500">Pemasukan Hari Ini</p>
      <strong class="mt-3 block text-2xl">Rp {{ formatter.format(stats.daily_revenue) }}</strong>
    </article>
    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <p class="text-sm text-slate-500">Transaksi Hari Ini</p>
      <strong class="mt-3 block text-2xl">{{ stats.total_transactions }}</strong>
    </article>
    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <p class="text-sm text-slate-500">Jumlah Produk</p>
      <strong class="mt-3 block text-2xl">{{ stats.product_count }}</strong>
    </article>
    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <p class="text-sm text-slate-500">Stok Menipis</p>
      <strong class="mt-3 block text-2xl text-amber-600">{{ stats.low_stock_count }}</strong>
    </article>
  </section>

  <section class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h2 class="mb-4 font-bold">Jadwal Hari Ini</h2>
    <EmptyState v-if="!loading && stats.today_schedule.length === 0" title="Belum ada jadwal hari ini." message="Jadwal piket akan tampil di sini setelah admin membuat jadwal." />
    <div v-else class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
      <article v-for="schedule in stats.today_schedule" :key="schedule.id" class="rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">{{ schedule.date }}</p>
        <strong class="mt-1 block">{{ schedule.user?.name }}</strong>
        <span class="mt-3 inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700">{{ schedule.shift }}</span>
      </article>
    </div>
  </section>
</template>

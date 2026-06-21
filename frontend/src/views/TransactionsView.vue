<script setup>
import { onMounted, ref } from 'vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import { api } from '../services/api'

const transactions = ref([])
const formatter = new Intl.NumberFormat('id-ID')

async function loadTransactions() {
  const { data } = await api.get('/transactions', { params: { per_page: 50 } })
  transactions.value = data.data
}

onMounted(loadTransactions)
</script>

<template>
  <PageHeader title="Transactions" subtitle="Riwayat transaksi sesuai hak akses pengguna." />

  <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <EmptyState v-if="transactions.length === 0" title="Belum ada transaksi." message="Transaksi kasir yang selesai akan tampil di sini." />
    <div v-else class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="border-b border-slate-200 text-slate-500">
          <tr>
            <th class="py-3">Tanggal</th>
            <th class="py-3">Petugas</th>
            <th class="py-3">Item</th>
            <th class="py-3">Total</th>
            <th class="py-3">Dibayar</th>
            <th class="py-3">Kembalian</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="transaction in transactions" :key="transaction.id">
            <td class="py-3">{{ new Date(transaction.created_at).toLocaleString('id-ID') }}</td>
            <td class="py-3">{{ transaction.user?.name }}</td>
            <td class="py-3">{{ transaction.items?.length || 0 }}</td>
            <td class="py-3 font-semibold">Rp {{ formatter.format(transaction.total_amount) }}</td>
            <td class="py-3">Rp {{ formatter.format(transaction.paid_amount) }}</td>
            <td class="py-3">Rp {{ formatter.format(transaction.change_amount) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

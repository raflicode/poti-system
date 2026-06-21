<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import { api } from '../services/api'

const products = ref([])
const search = ref('')
const editing = ref(null)
const form = reactive({ name: '', price: 0, stock: 0 })
const formatter = new Intl.NumberFormat('id-ID')

const filteredProducts = computed(() => {
  const term = search.value.toLowerCase().trim()
  return products.value.filter((product) => !term || product.name.toLowerCase().includes(term))
})

async function loadProducts() {
  const { data } = await api.get('/products', { params: { per_page: 100 } })
  products.value = data.data
}

function resetForm() {
  editing.value = null
  form.name = ''
  form.price = 0
  form.stock = 0
}

function editProduct(product) {
  editing.value = product
  form.name = product.name
  form.price = Number(product.price)
  form.stock = product.stock
}

async function saveProduct() {
  if (editing.value) {
    await api.put(`/products/${editing.value.id}`, form)
  } else {
    await api.post('/products', form)
  }
  resetForm()
  await loadProducts()
}

async function deleteProduct(product) {
  await api.delete(`/products/${product.id}`)
  await loadProducts()
}

onMounted(loadProducts)
</script>

<template>
  <PageHeader title="Products" subtitle="Kelola daftar produk, harga, stok, dan status.">
    <input v-model="search" class="w-72 rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-600" placeholder="Cari produk..." />
  </PageHeader>

  <section class="grid gap-6 xl:grid-cols-[360px_1fr]">
    <form class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" @submit.prevent="saveProduct">
      <h2 class="mb-4 font-bold">{{ editing ? 'Edit Produk' : 'Tambah Produk' }}</h2>
      <div class="grid gap-4">
        <label class="grid gap-2 text-sm font-medium">
          Nama produk
          <input v-model="form.name" class="rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-600" placeholder="Es Teh Manis" required />
        </label>
        <label class="grid gap-2 text-sm font-medium">
          Harga
          <input v-model.number="form.price" class="rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-600" min="0" type="number" required />
        </label>
        <label class="grid gap-2 text-sm font-medium">
          Stok
          <input v-model.number="form.stock" class="rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-600" min="0" type="number" required />
        </label>
        <div class="flex gap-2">
          <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" type="submit">Simpan</button>
          <button class="rounded-lg border border-slate-200 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50" type="button" @click="resetForm">Reset</button>
        </div>
      </div>
    </form>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <EmptyState v-if="filteredProducts.length === 0" title="Belum ada produk." message="Klik simpan pada form produk untuk membuat produk pertama." />
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="border-b border-slate-200 text-slate-500">
            <tr>
              <th class="py-3">Nama</th>
              <th class="py-3">Harga</th>
              <th class="py-3">Stok</th>
              <th class="py-3">Status</th>
              <th class="py-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="product in filteredProducts" :key="product.id">
              <td class="py-3 font-medium">{{ product.name }}</td>
              <td class="py-3">Rp {{ formatter.format(product.price) }}</td>
              <td class="py-3" :class="product.stock <= 5 ? 'font-semibold text-amber-600' : ''">{{ product.stock }}</td>
              <td class="py-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="product.status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'">
                  {{ product.status === 'active' ? 'Active' : 'Out of Stock' }}
                </span>
              </td>
              <td class="py-3 text-right">
                <button class="mr-2 rounded-lg border border-slate-200 px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-50" type="button" @click="editProduct(product)">Edit</button>
                <button class="rounded-lg border border-red-200 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-50" type="button" @click="deleteProduct(product)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

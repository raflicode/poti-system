<script setup>
import { computed, onMounted, ref } from 'vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import { api } from '../services/api'

const products = ref([])
const cart = ref([])
const search = ref('')
const paidAmount = ref(0)
const message = ref('')
const formatter = new Intl.NumberFormat('id-ID')

const filteredProducts = computed(() => {
  const term = search.value.toLowerCase().trim()
  return products.value.filter((product) => product.status === 'active' && (!term || product.name.toLowerCase().includes(term)))
})

const totalAmount = computed(() => cart.value.reduce((total, item) => total + Number(item.price) * item.quantity, 0))
const changeAmount = computed(() => Math.max(0, Number(paidAmount.value) - totalAmount.value))
const canCheckout = computed(() => cart.value.length > 0 && Number(paidAmount.value) >= totalAmount.value)

async function loadProducts() {
  const { data } = await api.get('/products', { params: { per_page: 100 } })
  products.value = data.data
}

function addToCart(product) {
  const existing = cart.value.find((item) => item.id === product.id)

  if (existing) {
    if (existing.quantity < product.stock) existing.quantity += 1
    return
  }

  cart.value.push({ ...product, quantity: 1 })
}

function updateQuantity(item, delta) {
  const nextQuantity = item.quantity + delta

  if (nextQuantity <= 0) {
    cart.value = cart.value.filter((entry) => entry.id !== item.id)
    return
  }

  if (nextQuantity <= item.stock) {
    item.quantity = nextQuantity
  }
}

async function checkout() {
  await api.post('/transactions', {
    paid_amount: paidAmount.value,
    items: cart.value.map((item) => ({ product_id: item.id, quantity: item.quantity })),
  })

  message.value = 'Transaksi berhasil disimpan.'
  cart.value = []
  paidAmount.value = 0
  await loadProducts()
}

onMounted(loadProducts)
</script>

<template>
  <PageHeader title="Cashier" subtitle="Alur transaksi cepat untuk piket POTI." />

  <p v-if="message" class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ message }}</p>

  <section class="grid gap-6 xl:grid-cols-[1fr_420px]">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="font-bold">Produk</h2>
        <input v-model="search" class="w-72 rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-600" placeholder="Cari produk..." />
      </div>

      <EmptyState v-if="filteredProducts.length === 0" title="Produk tidak tersedia." message="Produk aktif akan tampil di sini saat stok tersedia." />
      <div v-else class="grid gap-3 md:grid-cols-2">
        <button
          v-for="product in filteredProducts"
          :key="product.id"
          class="rounded-xl border border-slate-200 p-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
          type="button"
          @click="addToCart(product)"
        >
          <strong class="block">{{ product.name }}</strong>
          <span class="mt-1 block text-sm text-slate-500">Rp {{ formatter.format(product.price) }}</span>
          <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Stok {{ product.stock }}</span>
        </button>
      </div>
    </div>

    <aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <h2 class="mb-4 font-bold">Keranjang</h2>
      <EmptyState v-if="cart.length === 0" title="Keranjang kosong." message="Pilih produk dari daftar untuk memulai transaksi." />

      <div v-else class="grid gap-3">
        <article v-for="item in cart" :key="item.id" class="rounded-xl border border-slate-200 p-4">
          <div class="flex items-start justify-between gap-3">
            <div>
              <strong>{{ item.name }}</strong>
              <p class="text-sm text-slate-500">Rp {{ formatter.format(item.price) }}</p>
            </div>
            <div class="flex items-center gap-2">
              <button class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 font-bold" type="button" @click="updateQuantity(item, -1)">-</button>
              <span class="w-8 text-center font-semibold">{{ item.quantity }}</span>
              <button class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 font-bold" type="button" @click="updateQuantity(item, 1)">+</button>
            </div>
          </div>
        </article>
      </div>

      <div class="mt-5 grid gap-3 border-t border-slate-200 pt-5">
        <div class="flex items-center justify-between">
          <span class="text-slate-500">Total</span>
          <strong class="text-xl">Rp {{ formatter.format(totalAmount) }}</strong>
        </div>
        <label class="grid gap-2 text-sm font-medium">
          Uang diterima
          <input v-model.number="paidAmount" class="rounded-lg border border-slate-300 px-3 py-2 text-right outline-none focus:border-blue-600" min="0" type="number" />
        </label>
        <div class="flex items-center justify-between rounded-xl bg-blue-50 px-4 py-3">
          <span class="font-medium text-blue-700">Kembalian</span>
          <strong class="text-blue-700">Rp {{ formatter.format(changeAmount) }}</strong>
        </div>
        <button class="rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700 disabled:opacity-50" type="button" :disabled="!canCheckout" @click="checkout">
          Checkout
        </button>
      </div>
    </aside>
  </section>
</template>

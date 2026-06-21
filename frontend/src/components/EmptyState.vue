<script setup>
defineProps({
  title: { type: String, required: true },
  message: { type: String, required: true },
})
</script>

<template>
  <div class="grid place-items-center rounded-xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
    <div class="mb-3 grid h-10 w-10 place-items-center rounded-full bg-blue-50 text-blue-700">i</div>
    <p class="font-semibold">{{ title }}</p>
    <p class="mt-1 max-w-sm text-sm text-slate-500">{{ message }}</p>
  </div>
</template>

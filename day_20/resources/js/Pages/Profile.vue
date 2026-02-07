<template>
  <section>
    <div>
      <h1>Профиль</h1>
      <p>Loremipsum, a dictum felis. Nunc eget fermentum enim. Vivamus  lobortis nec justo. Integer laoreet porttitor tristique.</p>

      <div style="margin-top: 20px; display: flex; gap: 12px;">
        <button @click="reloadWithPreserve">
          Обновить данные (preserve)
        </button>

        <button @click="reloadWithoutPreserve">
          Обновить данные (без preserve)
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { defineOptions } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from './App.vue'

defineOptions({
  layout: AppLayout,
})


</script>
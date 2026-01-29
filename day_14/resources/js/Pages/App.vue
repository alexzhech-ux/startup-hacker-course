<template>
  <div class="app">
    <nav>
      <Link href="/">Главная</Link>
      <Link href="/about-us">О нас</Link>
      <Link href="/dashboard">Панель</Link>
      <Link href="/profile">Профиль</Link>
    </nav>

    <slot />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
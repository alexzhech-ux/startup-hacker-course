<template>
  <div class="app">
    <nav>
      <Link href="/">Главная</Link>
      <Link href="/about-us">О нас</Link>
      <Link href="/dashboard">Панель</Link>

      <Link v-if="user" href="/profile">Профиль</Link>
      <Link v-if="user" href="/my-books">Мои книги</Link>
    </nav>

    <div class="userDataBox">
      <template v-if="!user">
        <SButton @click="openLogin">Вход</SButton>
      </template>

      <template v-else>
        <p>{{ user.username }}</p>

        <form method="post" action="/logout">
          <input type="hidden" name="_token" :value="csrf">
          <SButton type="submit">Выйти</SButton>
        </form>
      </template>
    </div>

    <AuthForm
      v-if="showAuth"
      :mode="authMode"
      @close="showAuth = false"
      @switch="switchMode"
    />

    <slot />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { SButton } from 'startup-ui'
import AuthForm from '@/Components/Auth/AuthForm.vue'

const page = usePage()

const user = computed(() => page.props.auth?.user ?? null)
const csrf = page.props.csrf_token

const showAuth = ref(false)
const authMode = ref('login')

function openLogin() {
  authMode.value = 'login'
  showAuth.value = true
}

function switchMode(mode) {
  authMode.value = mode
}
</script>
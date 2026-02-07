<template>
  <div class="app">
    <h1>{{ title }}</h1>

    <p>Всего книг: {{ totalBooks }}</p>

    <p v-if="$page.props.auth?.user">
      Привет, {{ $page.props.auth.user.username }}
    </p>
    <p v-else>
      Пользователь не авторизован
    </p>

    <ul class="booksList">
      <BookCard
        v-for="(book, index) in uiBooks"
        :key="book.id"
        :book="book"
        :index="index"
        @edit="onEditBook"
        @delete="onDeleteBook"
        @update:rating="onRateBook"
      />
    </ul>

    <div class="addBookButtonPosition" v-if="$page.props.auth?.user">
      <SButton class="addBookButton" @click="showAddForm">
        Добавить
      </SButton>
    </div>

    <Dialog v-if="isFormVisible" :title="dialogTitle">
      <BookForm
        :key="isEditMode ? currentBookId : 'new'"
        :model-value="form"
        :options="genreOptions"
        :isEditMode="isEditMode"
        @submit="submitForm"
        @cancel="closeForm"
      />
    </Dialog>

    <div class="ratingSummary">
      <h2>Общий рейтинг</h2>
      <p>Всего книг: <strong>{{ totalBooks }}</strong></p>
      <p>Средний рейтинг: <strong>{{ averageRating }}</strong></p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, defineOptions } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

import AppLayout from './App.vue'
import BookCard from './BookCard.vue'
import Dialog from './Dialog.vue'
import BookForm from './BookForm.vue'

defineOptions({ layout: AppLayout })

const page = usePage()

const props = defineProps({
  books: { type: Array, default: () => [] },
  title: String,
})

const emptyForm = () => ({
  id: null,
  title: '',
  description: '',
  genres: [],
  is_for_adult: false,
  cover: '',
})

const form = ref(emptyForm())
const isFormVisible = ref(false)
const isEditMode = ref(false)
const bookFormRef = ref(null)

const genreOptions = [
  { value: 1, label: 'Повесть' },
  { value: 2, label: 'Эпос' },
  { value: 3, label: 'Лирика' },
  { value: 4, label: 'Драма' },
  { value: 5, label: 'Эротика' },
  { value: 6, label: 'Фэнтези' },
  { value: 7, label: 'Фантастика' },
  { value: 8, label: 'Справочник' },
  { value: 9, label: 'Учебник' },
  { value: 10, label: 'Эссе' },
]

const userId = computed(() => page.props.auth?.user?.id)

const userBooks = computed(() =>
  props.books.filter(b => b.user_id === userId.value)
)

const totalBooks = computed(() => userBooks.value.length)

const averageRating = computed(() => {
  if (!userBooks.value.length) return '0.00'

  const sum = userBooks.value.reduce((acc, b) => {
    return acc +
      b.rating_1 * 1 +
      b.rating_2 * 2 +
      b.rating_3 * 3 +
      b.rating_4 * 4 +
      b.rating_5 * 5
  }, 0)

  return (sum / userBooks.value.length).toFixed(2)
})

const uiBooks = computed(() =>
  userBooks.value.map(b => ({
    id: b.id,
    title: b.title,
    description: b.description,
    cover: b.cover || '/img/default-cover.png',

    genres: Array.isArray(b.genres)
      ? b.genres.map(g => g.id)
      : [],

    genreNames: Array.isArray(b.genres)
      ? b.genres.map(g => g.name)
      : [],

    rating:
      b.rating_1 * 1 +
      b.rating_2 * 2 +
      b.rating_3 * 3 +
      b.rating_4 * 4 +
      b.rating_5 * 5,

    is_for_adult: !!b.is_for_adult,
  }))
)

const dialogTitle = computed(() =>
  isEditMode.value ? 'Редактирование книги' : 'Новая книга'
)

const showAddForm = async () => {
  isEditMode.value = false
  form.value = emptyForm()
  isFormVisible.value = true
  await nextTick()
  bookFormRef.value?.focusTitle?.()
}

const onEditBook = book => {
  isEditMode.value = true

  form.value = {
    id: book.id,
    title: book.title,
    description: book.description,
    genres: [...book.genres],
    is_for_adult: !!book.is_for_adult,
    cover: book.cover,
  }

  isFormVisible.value = true
}

const submitForm = () => {
  if (isEditMode.value) {
    router.put(`/books/${form.value.id}`, form.value)
  } else {
    router.post('/books', form.value)
  }

  closeForm()
}

const onDeleteBook = id => {
  if (confirm('Удалить книгу?')) {
    router.delete(`/books/${id}`)
  }
}

const onRateBook = ({ bookId, rating }) => {
  router.post(`/books/${bookId}/rate`, { rating })
}

const closeForm = () => {
  isFormVisible.value = false
  isEditMode.value = false
  form.value = emptyForm()
}
</script>

<style scoped lang="scss">
.booksList {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  padding: 0;
}

.addBookButtonPosition {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.addBookButton {
  padding: 10px 20px;
  font-size: 22px;
  border-radius: 30px;
}

.ratingSummary {
  margin-top: 40px;
  text-align: center;
}
</style>
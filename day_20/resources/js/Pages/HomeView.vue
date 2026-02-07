<template>
  <div>
    <h1>{{ title }}</h1>

  </div>

  <div class="app">
    <Dialog
      v-if="isFormVisible"
      :title="dialogTitle"
      @cancel="closeForm"
    >
      <BookForm
        ref="bookFormRef"
        v-model="form"
        :options="options"
        :isEditMode="isEditMode"
        @submit="submitForm"
        @cancel="closeForm"
      />
    </Dialog>

    <ul class="booksList">
      <BookCard
        v-for="(book, index) in localBooks"
        :key="index"
        :book="book"
        :index="index"
        @edit="onEditBook"
        @delete="onDeleteBook"
        @update:rating="onRateBook"
      />
    </ul>

    <div class="addBookButtonPosition">
      <SButton class="addBookButton" @click="showAddForm">
        Добавить
      </SButton>
    </div>

    <div class="ratingSummary">
      <SButton class="resetRatingButton" @click="resetAllRatings">
        Сбросить рейтинг
      </SButton>

      <div class="ratingInfo">
        <h2>Общий рейтинг</h2>
        <p>Всего книг: <strong>{{ totalBooks }}</strong></p>
        <p>Средний рейтинг: <strong>{{ averageRating }}</strong></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick, defineOptions } from 'vue'

import BookCard from './BookCard.vue'
import Dialog from './Dialog.vue'
import BookForm from './BookForm.vue'
import AppLayout from './App.vue'

defineOptions({
  layout: AppLayout,
})

defineProps({
  count: Number,
  title: String,
  books: Array,
  author: Object,
})

const localBooks = ref([
  {
    title: 'Старик и море',
    description: 'Повесть о старом рыбаке.',
    genres: ['Повесть'],
    cover: '/img/1.png',
    isForAdult: false,
    rating: 0,
  },
  {
    title: 'Камасутра',
    description: 'Собрание поз.',
    genres: ['Эротика'],
    cover: '/img/2.png',
    isForAdult: true,
    rating: 2,
  },
  {
    title: 'Сильмариллион',
    description: 'Фэнтези о богах.',
    genres: ['Фэнтези'],
    cover: '/img/3.png',
    isForAdult: false,
    rating: 5,
  },
  {
    title: 'Краткая история времени',
    description: 'О вселенной.',
    genres: ['Эссе'],
    cover: '/img/4.png',
    isForAdult: false,
    rating: 3,
  },
  {
    title: 'Искусство войны',
    description: 'Древний трактат.',
    genres: ['Эссе'],
    cover: '/img/5.png',
    isForAdult: false,
    rating: 4,
  },
])

const emptyForm = () => ({
  title: '',
  description: '',
  genres: [],
  isForAdult: false,
  cover: '',
  coverAlt: '',
  coverFile: null,
  coverUrl: '',
})

const form = ref(emptyForm())
const isFormVisible = ref(false)
const isEditMode = ref(false)
const editedBookIndex = ref(null)
const bookFormRef = ref(null)

const totalBooks = computed(() => localBooks.value.length)

const averageRating = computed(() => {
  if (!localBooks.value.length) return '0.00'
  const sum = localBooks.value.reduce((a, b) => a + (b.rating || 0), 0)
  return (sum / localBooks.value.length).toFixed(2)
})

const dialogTitle = computed(() =>
  isEditMode.value && form.value.title
    ? `Книга: ${form.value.title}`
    : 'Книга'
)

const showAddForm = () => {
  isEditMode.value = false
  editedBookIndex.value = null
  form.value = emptyForm()
  isFormVisible.value = true
}

const showEditForm = (book, index) => {
  isEditMode.value = true
  editedBookIndex.value = index
  form.value = {
    title: book.title,
    description: book.description,
    genres: [...book.genres],
    isForAdult: book.isForAdult,
    cover: book.cover,
    coverAlt: book.coverAlt || book.title,
    coverFile: null,
    coverUrl: '',
  }
  isFormVisible.value = true
}

const submitForm = () => {
  if (isEditMode.value) {
    Object.assign(localBooks.value[editedBookIndex.value], {
      title: form.value.title,
      description: form.value.description,
      genres: form.value.genres,
      isForAdult: form.value.isForAdult,
      cover: form.value.cover,
      coverAlt: form.value.coverAlt || form.value.title,
    })
  } else {
    localBooks.value.push({
      title: form.value.title,
      description: form.value.description,
      genres: form.value.genres,
      isForAdult: form.value.isForAdult,
      cover: form.value.cover || '/img/default-cover.png',
      coverAlt: form.value.coverAlt || form.value.title,
      rating: 0,
    })
  }
  closeForm()
}

const closeForm = () => {
  isFormVisible.value = false
  isEditMode.value = false
  editedBookIndex.value = null
  form.value = emptyForm()
}

const onEditBook = index => showEditForm(localBooks.value[index], index)
const onDeleteBook = index => localBooks.value.splice(index, 1)
const onRateBook = ({ index, value }) => (localBooks.value[index].rating = value)

const resetAllRatings = () => {
  localBooks.value.forEach(b => (b.rating = 0))
}

const options = ref([
  { value: '1', text: 'Повесть' },
  { value: '2', text: 'Эпос' },
  { value: '3', text: 'Лирика' },
  { value: '4', text: 'Драма' },
  { value: '5', text: 'Эротика' },
  { value: '6', text: 'Фэнтези' },
  { value: '7', text: 'Фантастика' },
  { value: '8', text: 'Справочник' },
  { value: '9', text: 'Учебник' },
  { value: '10', text: 'Эссе' },
])

watch(isFormVisible, async visible => {
  if (visible) {
    await nextTick()
    bookFormRef.value?.focusTitle()
  }
})
</script>

<style scoped lang="scss">
.booksList {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
}

.addBookButtonPosition {
  width: 100%;
  display: flex;
  justify-content: center;
}

.addBookButton {
  padding: 10px 20px;
  font-size: 28px;
  border-radius: 30px;
}
</style>

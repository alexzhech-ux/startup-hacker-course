<template>
  <div class="app">
    <Dialog
      :visible="isFormVisible"
      :title="dialogTitle"
      @cancel="closeForm"
    >
      <BookForm
        v-model="form"
        :options="options"
        :isEditMode="isEditMode"
        @submit="submitForm"
        @cancel="closeForm"
      />
    </Dialog>
    <ul class="booksList">
      <BookCard
        v-for="(book, index) in books"
        :key="index"
        :book="book"
        :index="index"
        @edit="onEditBook"
        @delete="onDeleteBook"
        @update:rating="onRateBook"
      />
    </ul>
    <div class="addBookButtonPosition">
      <button class="addBookButton" @click="showAddForm">Добавить</button>
    </div>
    <div class="">
      <button class="" @click="resetAllRatings">Сбросить рейтинг</button>
      <div>
        <h2>Общий рейтинг</h2>
        <p>Всего книг: <span>{{ totalBooks }}</span></p>
        <p>Средний рейтинг: <span>{{ averageRating }}</span></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import BookCard from './BookCard.vue'
import Dialog from './Dialog.vue'
import BookForm from './BookForm.vue'
const totalBooks = computed(() => books.value.length)
const isFormVisible = ref(false)
const isEditMode = ref(false)
const editedBookIndex = ref(null)
const DEFAULT_GENRE = 'Повесть'
const form = ref({
  title: '',
  description: '',
  genre: DEFAULT_GENRE,
  isForAdult: false,
  cover: '',
  coverAlt: '',
  coverFile: null
})
const onEditBook = (index) => {
  const book = books.value[index]
  showEditForm(book, index)
}
const onDeleteBook = (index) => {
  if (!confirm('Удалить книгу?')) return
  books.value.splice(index, 1)
}
const onRateBook = ({ index, value }) => {
  books.value[index].rating = value
}
const showAddForm = () => {
  isEditMode.value = false
  editedBookIndex.value = null
  resetForm()
  isFormVisible.value = true
}
const showEditForm = (item, index) => {
  isEditMode.value = true
  editedBookIndex.value = index
  form.value = {
    title: item.title,
    description: item.description,
    genre: item.genre || DEFAULT_GENRE,
    isForAdult: item.isForAdult,
    cover: item.cover,
    coverAlt: item.coverAlt || item.title,
    coverFile: null
  }
  isFormVisible.value = true
}
const dialogTitle = computed(() => {
  if (isEditMode.value && form.value.title) {
    return `Книга: ${form.value.title}`
  }
  return 'Книга'
})
const submitForm = () => {
  if (isEditMode.value) {
    const book = books.value[editedBookIndex.value]
    book.title = form.value.title
    book.description = form.value.description
    book.genre = form.value.genre
    book.isForAdult = form.value.isForAdult
    book.coverAlt = form.value.coverAlt || form.value.title
    if (form.value.coverFile) {
      book.cover = form.value.cover
    }
  } else {
    books.value.push({
      title: form.value.title,
      description: form.value.description,
      genre: form.value.genre,
      isForAdult: form.value.isForAdult,
      cover: form.value.cover || '/img/default-cover.png',
      coverAlt: form.value.coverAlt || form.value.title,
      rating: 0,
    })
  }
  closeForm()
}
const resetForm = () => {
  form.value = {
    title: '',
    description: '',
    genre: DEFAULT_GENRE,
    isForAdult: false,
    cover: '',
    coverAlt: '',
    coverFile: null,
  }
}
const closeForm = () => {
  isFormVisible.value = false
  isEditMode.value = false
  editedBookIndex.value = null
  resetForm()
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
  { value: '10', text: 'Эссе' }
])
const books = ref([
  {
    title: 'Старик и море',
    description: 'Повесть о старом рыбаке, его борьбе и воспоминаниях.',
    genre: 'Повесть',
    cover: './img/1.png',
    isForAdult: false,
    rating: 0
  },
  {
    title: 'Камасутра',
    description: 'Собрание поз для соития',
    genre: 'Эротика',
    cover: './img/2.png',
    isForAdult: true,
    rating: 2
  },
  {
    title: 'Сильмариллион',
    description: 'Роман о сказочных богах, существах, их борьбе, корысти и верности.',
    genre: 'Фэнтези',
    cover: './img/3.png',
    isForAdult: false,
    rating: 5
  },
  {
    title: 'Краткая история времени',
    description: 'Роман о представлении законов вселенной.',
    genre: 'Эссе',
    cover: './img/4.png',
    isForAdult: false,
    rating: 3
  },
  {
    title: 'Исскуство войны',
    description: 'Древнекитайский трактат по ведению войны.',
    genre: 'Эссе',
    cover: './img/5.png',
    isForAdult: false,
    rating: 4
  }
])
const resetAllRatings = () => {
  books.value.forEach(book => {
    book.rating = 0
    book.hoverRating = null
  })
}
const averageRating = computed(() => {
  if (books.value.length === 0) return '0.00'
  const sum = books.value.reduce((acc, book) => acc + (book.rating || 0), 0)
  const avg = sum / books.value.length
  return avg.toFixed(2)
})
watch(isFormVisible, (visible) => {
  document.body.style.overflow = visible ? 'hidden' : ''
})
</script>

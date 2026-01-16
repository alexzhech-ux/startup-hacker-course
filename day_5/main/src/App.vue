<template>
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
    <div class="ratingSummary">
      <button class="resetRatingButton" @click="resetAllRatings">
        Сбросить рейтинг
      </button>

      <div class="ratingInfo">
        <h2>Общий рейтинг</h2>
        <p>Всего книг: <strong>{{ totalBooks }}</strong></p>
        <p>Средний рейтинг: <strong>{{ averageRating }}</strong></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed, nextTick } from 'vue'
import BookCard from './BookCard.vue'
import Dialog from './Dialog.vue'
import BookForm from './BookForm.vue'
const totalBooks = computed(() => books.value.length)
const isFormVisible = ref(false)
const isEditMode = ref(false)
const editedBookIndex = ref(null)
const bookFormRef = ref(null)
const form = ref({
  title: '',
  description: '',
  genres: [],
  isForAdult: false,
  cover: '',
  coverAlt: '',
  coverFile: null,
  coverUrl: ''
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
    genres: Array.isArray(item.genres) ? item.genres : [item.genre],
    isForAdult: item.isForAdult,
    cover: item.cover,
    coverAlt: item.coverAlt || item.title,
    coverFile: null,
    coverUrl: item.cover?.startsWith('http') ? item.cover : ''
  }
  isFormVisible.value = true
}
const dialogTitle = computed(() => {
  if (isEditMode.value && form.value.title) {
    return `Книга: ${form.value.title}`
  }
  return 'Книга'
})
const resolveCover = () => {
  if (form.value.coverFile) return form.value.cover
  if (form.value.coverUrl) return form.value.coverUrl
  return '/img/default-cover.png'
}
const submitForm = () => {
  if (isEditMode.value) {
    const book = books.value[editedBookIndex.value]
    book.title = form.value.title
    book.description = form.value.description
    book.genres = form.value.genres
    book.isForAdult = form.value.isForAdult
    book.coverAlt = form.value.coverAlt || form.value.title
    if (form.value.coverFile || form.value.coverUrl) {
      book.cover = resolveCover()
    }
  } else {
    books.value.push({
      title: form.value.title,
      description: form.value.description,
      genres: form.value.genres,
      isForAdult: form.value.isForAdult,
      cover: resolveCover(),
      coverAlt: form.value.coverAlt || form.value.title,
      rating: 0
    })
  }
  closeForm()
}
const resetForm = () => {
  form.value = {
    title: '',
    description: '',
    genres: [],
    isForAdult: false,
    cover: '',
    coverAlt: '',
    coverFile: null,
    coverUrl: ''
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
    genres: ['Повесть'],
    cover: './img/1.png',
    isForAdult: false,
    rating: 0
  },
  {
    title: 'Камасутра',
    description: 'Собрание поз для соития',
    genres: ['Эротика'],
    cover: './img/2.png',
    isForAdult: true,
    rating: 2
  },
  {
    title: 'Сильмариллион',
    description: 'Роман о сказочных богах, существах, их борьбе, корысти и верности.',
    genres: ['Фэнтези'],
    cover: './img/3.png',
    isForAdult: false,
    rating: 5
  },
  {
    title: 'Краткая история времени',
    description: 'Роман о представлении законов вселенной.',
    genres: ['Эссе'],
    cover: './img/4.png',
    isForAdult: false,
    rating: 3
  },
  {
    title: 'Исскуство войны',
    description: 'Древнекитайский трактат по ведению войны.',
    genres: ['Эссе'],
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
watch(() => form.value.coverUrl, (url) => {
  if (url) {
    form.value.coverFile = null
    form.value.cover = url
  }
})
watch(isFormVisible, async (visible) => {
  if (visible) {
    await nextTick()
    bookFormRef.value?.focusTitle()
  }
})
</script>

<style scoped lang="scss">
.dialog-fade-slide-enter-from {
  opacity: 0;
  transform: translateY(40px);
}

.dialog-fade-slide-enter-active {
  transition: opacity 300ms ease, transform 300ms ease;
}

.dialog-fade-slide-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.dialog-fade-slide-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.dialog-fade-slide-leave-active {
  transition: opacity 200ms ease, transform 200ms ease;
}

.dialog-fade-slide-leave-to {
  opacity: 0;
  transform: translateY(40px);
}

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
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
  import { ref, computed, watch, nextTick } from 'vue'
  import BookCard from './BookCard.vue'
  import Dialog from './Dialog.vue'
  import BookForm from './BookForm.vue'
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
      description: 'Роман о сказочных богах.',
      genres: ['Фэнтези'],
      cover: './img/3.png',
      isForAdult: false,
      rating: 5
    },
    {
      title: 'Краткая история времени',
      description: 'Роман о законах вселенной.',
      genres: ['Эссе'],
      cover: './img/4.png',
      isForAdult: false,
      rating: 3
    },
    {
      title: 'Искусство войны',
      description: 'Древнекитайский трактат.',
      genres: ['Эссе'],
      cover: './img/5.png',
      isForAdult: false,
      rating: 4
    }
  ])
  const form = ref(emptyForm())
  const isFormVisible = ref(false)
  const isEditMode = ref(false)
  const editedBookIndex = ref(null)
  const bookFormRef = ref(null)
  function emptyForm () {
    return {
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
  const totalBooks = computed(() => books.value.length)
  const averageRating = computed(() => {
    if (!books.value.length) return '0.00'
    const sum = books.value.reduce((a, b) => a + (b.rating || 0), 0)
    return (sum / books.value.length).toFixed(2)
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
      coverUrl: book.cover?.startsWith('http') ? book.cover : ''
    }
    isFormVisible.value = true
  }
  const submitForm = () => {
    if (isEditMode.value) {
      Object.assign(books.value[editedBookIndex.value], {
        title: form.value.title,
        description: form.value.description,
        genres: form.value.genres,
        isForAdult: form.value.isForAdult,
        cover: form.value.cover,
        coverAlt: form.value.coverAlt || form.value.title
      })
    } else {
      books.value.push({
        title: form.value.title,
        description: form.value.description,
        genres: form.value.genres,
        isForAdult: form.value.isForAdult,
        cover: form.value.cover || '/img/default-cover.png',
        coverAlt: form.value.coverAlt || form.value.title,
        rating: 0
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
  const onEditBook = index => showEditForm(books.value[index], index)
  const onDeleteBook = index => {
    books.value.splice(index, 1)
  }
  const onRateBook = ({ index, value }) => {
    books.value[index].rating = value
  }
  const resetAllRatings = () => {
    books.value.forEach(b => {
      b.rating = 0
      b.hoverRating = null
    })
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
  watch(isFormVisible, async visible => {
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
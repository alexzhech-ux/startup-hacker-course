<template>
  <div class="app">
    <div class="addForm_container" :class="{ disabled: !isFormVisible }" @click.self="closeForm">
      <form action="" method="get" class="addForm" @click.stop>
        <div class="addForm_fieldContainer">
          <label class="addForm_label" for="name">Введите название книги</label>
          <input class="addForm_text" type="text" placeholder="Старик и море" v-model="form.title"/>
        </div>
        <div class="addForm_fieldContainer">
          <label  class="addForm_label" for="story">Введите описание книги</label>
          <textarea class="addForm_text addForm_textarea" placeholder="Это рассказ о..." v-model="form.description"></textarea>
        </div>
        <div class="addForm_fieldContainer">
          <label class="addForm_label">Выберите обложку книги</label>
          <input
            type="file"
            accept="image/*"
            @change="onCoverChange"
            :disabled="!!form.coverUrl"
          />
        </div>

        <div class="addForm_fieldContainer">
          <label class="addForm_label">Или введите путь к обложке</label>
          <input
            class="addForm_text"
            type="text"
            placeholder="http://..."
            v-model="form.coverUrl"
            :disabled="!!form.coverFile"
          />
        </div>
        <div class="addForm_fieldContainer">
          <label class="addForm_label">Введите описание обложки</label>
          <input class="addForm_text" type="text" placeholder="Книга «Старик и море»" v-model="form.coverAlt"/>
        </div>
        <div class="addForm_fieldContainer">
          <label  class="addForm_label"for="name">Выберите жанр книги: </label>
          <select v-model="form.genre">
            <option v-for="option in options" :key="option.value" :value="option.text">{{ option.text }}</option>
          </select>
        </div>
        <div class="addForm_fieldContainer">
          <label  class="addForm_label" for="checkbox">Отметьте если книга 18+</label>
          <input type="checkbox" v-model="form.isForAdult" />
        </div>
        <div class="addForm_buttonPosition">
          <button type="button" class="addForm_button" @click="submitForm">{{ isEditMode ? 'Редактировать' : 'Добавить' }}</button>
        </div>
      </form>
    </div>
    <ul class="booksList">
      <li class="bookItem" v-for="(item, index) in books" :key="index">
        <div class="bookTitleLine">
          <h3 class="bookTitle">{{ item.title }}</h3>
        </div>
        <button class="bookItem_editButton" @click="showEditForm(item, index)">
          <img class="bookItem_editButtonIcon" src="/img/icons/edit.png" :alt="item.title"/>
        </button>
        <div class="bookItemContent">
          <div class="bookCoverBlock">
            <div class="bookCoverBlock_rating">
              <img :src="item.rating > 0 ? '/img/icons/ratingStar.png' : '/img/icons/ratingNonStar.png'" :alt="item.title" class="bookCoverBlock_ratingStar"/>
              <div class="bookCoverBlock_ratingVolPositioner">
                <p class="bookCoverBlock_ratingVol">{{ item.rating > 0 ? item.rating : '' }}</p>
              </div>
            </div>
            <img :src="item.cover" :alt="item.coverAlt || item.title" class="bookCover"/>
          </div>
          <div class="bookItemContent_bookData">
            <p class="bookDescription">{{ item.description }}</p>
            <p class="bookgenre">Жанр: {{ item.genre }}</p>
            <p class="bookRating">
              Рейтинг:
              <span v-for="star in 5" :key="star" class="bookRating_star" :class="{
                  bookRating_stars: star <= (item.hoverRating ?? item.rating),
                  bookRating_starsDis: star > (item.hoverRating ?? item.rating)
                }"
                @mouseenter="item.hoverRating = star"
                @mouseleave="item.hoverRating = null"
                @click="item.rating = star"
              >★</span>
            </p>
            <p class="bookAgeLimit">Возрастное ограничение: {{ item.isForAdult ? '18+' : 'Для всех возрастов' }}</p>
          </div>
        </div>
      </li>
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
  coverFile: null,
  coverUrl: ''
})
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
const onCoverChange = (event) => {
  const file = event.target.files[0]
  if (!file) return
  form.value.coverFile = file
  form.value.coverUrl = ''
  form.value.cover = URL.createObjectURL(file)
}
const submitForm = () => {
  if (isEditMode.value) {
    const book = books.value[editedBookIndex.value]
    book.title = form.value.title
    book.description = form.value.description
    book.genre = form.value.genre
    book.isForAdult = form.value.isForAdult
    book.coverAlt = form.value.coverAlt || form.value.title
    if (form.value.coverFile || form.value.coverUrl) {
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
      rating: 0
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
    genre: 'Повесть',
    cover: './img/1.png',
    isForAdult: false,
    rating: 0,
    hoverRating: null
  },
  {
    title: 'Камасутра',
    description: 'Собрание поз для соития',
    genre: 'Эротика',
    cover: './img/2.png',
    isForAdult: true,
    rating: 2,
    hoverRating: null
  },
  {
    title: 'Сильмариллион',
    description: 'Роман о сказочных богах, существах, их борьбе, корысти и верности.',
    genre: 'Фэнтези',
    cover: './img/3.png',
    isForAdult: false,
    rating: 5,
    hoverRating: null
  },
  {
    title: 'Краткая история времени',
    description: 'Роман о представлении законов вселенной.',
    genre: 'Эссе',
    cover: './img/4.png',
    isForAdult: false,
    rating: 3,
    hoverRating: null
  },
  {
    title: 'Исскуство войны',
    description: 'Древнекитайский трактат по ведению войны.',
    genre: 'Эссе',
    cover: './img/5.png',
    isForAdult: false,
    rating: 4,
    hoverRating: null
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
</script>

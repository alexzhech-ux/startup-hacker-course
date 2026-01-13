<template>
  <div class="app">
    <ul class="booksList">
      <li class="bookItem" v-for="(item, index) in books" :key="index">
        <div class="bookTitleLine">
          <h3 class="bookTitle">{{ item.title }}</h3>
        </div>
        <div class="bookItemContent">
          <img :src="item.cover" :alt="item.title" class="bookCover"/>
          <div class="bookItemContent_bookData">
            <p class="bookDescription">{{ item.description }}</p>
            <p class="bookgenre">Жанр: {{ item.genre }}</p>
            <p class="bookRating">Рейтинг: <span class="bookRating_stars" v-for="n in item.rating" :key="n">★</span></p>
            <p class="bookAgeLimit">
              Возрастное ограничение: {{ item.isForAdult ? '18+' : 'Для всех возрастов' }}
            </p>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>
<script setup>
import { ref } from 'vue'

const books = ref([
  {
    title: 'Старик и море',
    description: 'Повесть о старом рыбаке, его борьбе и воспоминаниях.',
    genre: 'Повесть',
    cover: './img/1.png',
    isForAdult: false,
    rating: 5,
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
</script>


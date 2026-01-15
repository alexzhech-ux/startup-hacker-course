<template>
  <li class="bookItem" :class="{ rated }">
    <div class="bookTitleLine">
      <h3 class="bookTitle">{{ book.title }}</h3>
    </div>
    <button class="bookItem_editButton" @click="emitEdit">
      <img class="bookItem_editButtonIcon" src="/img/icons/edit.png" :alt="book.title"/>
    </button>
    <button class="bookItem_deleteButton" @click="onDelete">✖</button>
    <div class="bookItemContent">
      <div class="bookCoverBlock">
        <div class="bookCoverBlock_rating">
          <img class="bookCoverBlock_ratingStar" :src="rated ? '/img/icons/ratingStar.png' : '/img/icons/ratingNonStar.png'"/>
          <div class="bookCoverBlock_ratingVolPositioner">
            <p class="bookCoverBlock_ratingVol">{{ rated ? book.rating : '' }}</p>
          </div>
        </div>
        <img class="bookCover" :src="book.cover" :alt="book.coverAlt || book.title"/>
      </div>
      <div class="bookItemContent_bookData">
        <p class="bookDescription">{{ book.description }}</p>
        <p class="bookgenre">Жанр: {{ book.genre }}</p>
        <p class="bookRating"> Рейтинг:
          <span
            v-for="star in 5"
            :key="star"
            class="bookRating_star"
            :class="{
              bookRating_stars: star <= (hoverRating ?? book.rating),
              bookRating_starsDis: star > (hoverRating ?? book.rating)
            }"
            @mouseenter="hoverRating = star"
            @mouseleave="hoverRating = null"
            @click="rate(star)"
          >★</span>
        </p>
        <p class="bookAgeLimit">Возрастное ограничение:{{ book.isForAdult ? '18+' : 'Для всех возрастов' }}</p>
      </div>
    </div>
  </li>
</template>

<script setup>
  import { ref, computed } from 'vue'
  const props = defineProps({
    book: { type: Object, required: true },
    index: { type: Number, required: true }
  })
  const emit = defineEmits(['edit', 'delete', 'update:rating'])
  const hoverRating = ref(null)
  const rated = computed(() => props.book.rating >= 1)
  const emitEdit = () => {
    emit('edit', props.index)
  }
  const onDelete = () => {
    emit('delete', props.index)
  }
  const rate = (value) => {
    emit('update:rating', {
      index: props.index,
      value
    })
  }
</script>
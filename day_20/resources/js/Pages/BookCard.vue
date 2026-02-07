<template>
  <li class="bookItem" :class="{ rated }">
    <div class="bookTitleLine">
      <h3 class="bookTitle">{{ book.title }}</h3>
    </div>

    <SActionIcon
      class="bookItem_editButton"
      icon="pen-to-square"
      title="Редактировать"
      @click="emitEdit"
    />

    <SActionIcon
      class="bookItem_deleteButton"
      icon="trash"
      danger
      title="Удалить"
      confirm="Вы действительно хотите удалить эту книгу?"
      @click="emitDelete"
    />

    <div class="bookItemContent">
      <div class="bookCoverBlock">
        <div class="bookCoverBlock_rating">
          <SImagePreview
            class="bookCoverBlock_ratingStar"
            :src="rated
              ? '/img/icons/ratingStar.png'
              : '/img/icons/ratingNonStar.png'"
          />
          <div class="bookCoverBlock_ratingVolPositioner">
            <p class="bookCoverBlock_ratingVol">
              {{ rated ? book.rating : '' }}
            </p>
          </div>
        </div>

        <SImagePreview
          class="bookCover"
          :src="book.cover"
          :alt="book.coverAlt || book.title"
        />
      </div>

      <div class="bookItemContent_bookData">
        <p class="bookDescription">{{ book.description }}</p>

        <p class="bookgenre">
          Жанры:
          <span v-if="book.genres.length">
            <span v-for="(genre, i) in book.genreNames" :key="i">
              {{ genre }}<span v-if="i < book.genreNames.length - 1">, </span>
            </span>
          </span>
          <span v-else>—</span>
        </p>

        <p class="bookRating">
          Рейтинг:
          <SActionIcon
            v-for="star in 5"
            :key="star"
            class="bookRating_star"
            :icon="star <= (hoverRating ?? book.rating)
              ? ['fas', 'star']
              : ['far', 'star']"
            @mouseenter="hoverRating = star"
            @mouseleave="hoverRating = null"
            @click="rate(star)"
          />
        </p>

        <p class="bookAgeLimit">
          Возрастное ограничение:
          {{ book.isForAdult ? '18+' : 'Для всех возрастов' }}
        </p>
      </div>
    </div>
  </li>
</template>

<script setup>
import { ref, computed } from 'vue'
import { SImagePreview, SActionIcon } from 'startup-ui'

const onSubmit = () => {
  emit('submit', {
    title: form.value.title,
    description: form.value.description,
    genres: form.value.genres,
    is_for_adult: form.value.is_for_adult,
  })
}

const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits([
  'edit',
  'delete',
  'update:rating',
])

const hoverRating = ref(null)

const rated = computed(() => Number(props.book.rating) >= 1)

const emitEdit = () => {
  emit('edit', props.book)
}

const emitDelete = () => {
  emit('delete', props.book.id)
}

const rate = value => {
  emit('update:rating', {
    bookId: props.book.id,
    rating: value,
  })
}
</script>

<style scoped lang="scss">
:deep(.s-actionicon svg) {
  width: 30px;
  height: 26px;
}

.bookItem {
  position: relative;
  justify-content: center;
  border: 2px solid black;
  border-radius: 30px;
  width: 650px;
  height: 600px;
  margin: 30px;
  padding: 30px;

  &.rated {
    border-color: gold;
  }

  &_deleteButton {
    position: absolute;
    left: 20px;
    top: 20px;
    background: none;
    font-size: 20px;
  }

  &_editButton {
    position: absolute;
    right: 20px;
    top: 20px;
    background: none;
  }
}

.bookItemContent {
  display: flex;
  flex-wrap: wrap;

  &_bookData {
    margin-left: 30px;
    width: 300px;
  }
}

.bookCover {
  width: 300px;
  height: 500px;
  border-radius: 30px;
  overflow: hidden;
}

.bookCover :deep(img) {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.bookCoverBlock_ratingStar {
  width: 50px;
  height: 50px;
  z-index: 100;
}

.bookCoverBlock_ratingStar :deep(img) {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.bookCoverBlock {
  position: relative;

  &_rating {
    position: absolute;
    left: 20px;
    top: 20px;
  }

  &_ratingVolPositioner {
    position: absolute;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 101;
  }
}

.bookTitleLine {
  width: 100%;
  display: flex;
  justify-content: center;
}

.bookTitle {
  font-size: 30px;
  padding-left: 20px;
}

.bookRating_star {
  cursor: pointer;
  color: gold;
  transition: color 0.2s ease;

  svg {
    width: 22px;
    height: 22px;
  }
}
</style>
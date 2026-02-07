<template>
  <form class="bookForm" @submit.prevent="onSubmit">
    <div class="formRow">
      <label>Название</label>
      <SInput
        ref="titleInput"
        v-model="form.title"
        placeholder="Название книги"
        required
      />
    </div>

    <div class="formRow">
      <label>Описание</label>
      <SInput
        type="textarea"
        v-model="form.description"
        placeholder="Описание книги"
      />
    </div>

    <div class="formRow">
      <label>Жанры</label>
      <select
        v-model="form.genres"
        multiple
        class="genre-select"
      >
        <option
          v-for="g in options"
          :key="g.value"
          :value="g.value"
        >
          {{ g.label }}
        </option>
      </select>
    </div>

    <div class="formRow checkboxRow">
      <SCheckbox v-model="form.is_for_adult">
        18+
      </SCheckbox>
    </div>

    <div class="formRow">
      <label>Обложка</label>
      <input type="file" @change="onCoverChange" />
      <SImagePreview
        v-if="coverPreview"
        class="coverPreview"
        :src="coverPreview"
      />
    </div>

    <div class="formActions">
      <button type="submit" class="s-button">
        {{ isEditMode ? 'Сохранить' : 'Добавить' }}
      </button>

      <button
        type="button"
        class="s-button secondary"
        @click="emit('cancel')"
      >
        Отмена
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  options: {
    type: Array,
    required: true,
  },
  isEditMode: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['submit', 'cancel'])

const form = ref({
  title: '',
  description: '',
  genres: [],
  is_for_adult: false,
  cover: null,
})

watch(
  () => props.modelValue,
  value => {
    form.value = {
      title: value?.title ?? '',
      description: value?.description ?? '',
      genres: Array.isArray(value?.genres)
        ? value.genres.map(g => g.id ?? g)
        : [],
      is_for_adult: !!value?.is_for_adult,
      cover: null,
    }
  },
  { immediate: true }
)

const coverPreview = ref(null)

const onCoverChange = e => {
  const file = e.target.files?.[0] || null
  form.value.cover = file
  coverPreview.value = file ? URL.createObjectURL(file) : null
}

const onSubmit = () => {
  const fd = new FormData()

  fd.append('title', form.value.title)
  fd.append('description', form.value.description ?? '')
  fd.append('is_for_adult', form.value.is_for_adult ? 1 : 0)

  form.value.genres.forEach(id => {
    fd.append('genres[]', id)
  })

  if (form.value.cover) {
    fd.append('cover', form.value.cover)
  }

  emit('submit', fd)
}

const titleInput = ref(null)
defineExpose({
  focusTitle: () => titleInput.value?.focus(),
})
</script>

<style scoped lang="scss">
.bookForm {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.formRow {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.checkboxRow {
  flex-direction: row;
  align-items: center;
}

.coverPreview {
  width: 500px;
  height: 500px;
  overflow: hidden;
  border-radius: 12px;
  position: relative;
}

.coverPreview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}


.formActions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
}

</style>
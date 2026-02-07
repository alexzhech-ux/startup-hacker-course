<template>
  <SForm
    class="addForm"
    v-model="form"
    @submit="onSubmit"
    :style="{ width: '500px' }"
  >
    <SFormRow title="Введите название книги" name="title">
      <SInput
        ref="titleInput"
        placeholder="Старик и море"
      />
    </SFormRow>
    <SFormRow title="Введите описание книги" name="description">
      <SInput
        type="textarea"
        placeholder="Это рассказ о..."
      />
    </SFormRow>
    <SFormRow title="Выберите обложку книги">
      <input
        type="file"
        accept="image/*"
        :disabled="!!form.coverUrl"
        @change="onCoverChange"
      />
    </SFormRow>
    <SFormRow title="Или введите путь к обложке" name="coverUrl">
      <SInput
        placeholder="http://..."
        :disabled="!!form.coverFile"
      />
    </SFormRow>
    <SFormRow title="Введите описание обложки" name="coverAlt">
      <SInput
        placeholder="Книга «Старик и море»"
      />
    </SFormRow>
    <SFormRow title="Выберите жанры книги">
      <SSelect
        v-model="selectedGenre"
        :options="genreOptions"
        filterable
        placeholder="Выберите жанр"
        @update:model-value="onGenreSelect"
      />
      <p class="selectedGenres" v-if="form.genres.length">
        Выбрано:
        <span v-for="(g, i) in form.genres" :key="i">
          {{ g }}<span v-if="i < form.genres.length - 1">, </span>
        </span>
      </p>
    </SFormRow>
    <SFormRow title="Книга 18+" name="isForAdult">
      <SCheckbox />
    </SFormRow>
    <div class="addForm_buttonPosition">
      <SButton type="submit">
        {{ isEditMode ? 'Редактировать' : 'Добавить' }}
      </SButton>
      <SButton
        type="button"
        variant="secondary"
        @click="emit('cancel')"
      >
        Отмена
      </SButton>
    </div>
  </SForm>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import debounce from 'lodash/debounce'
const props = defineProps({
  modelValue: {
    type: Object,
    required: true
  },
  options: {
    type: Array,
    required: true
  },
  isEditMode: {
    type: Boolean,
    default: false
  }
})
const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])
const form = ref({ ...props.modelValue })
watch(
  form,
  value => emit('update:modelValue', value),
  { deep: true }
)
const genreOptions = computed(() =>
  Object.fromEntries(
    props.options.map(o => [o.value, o.text])
  )
)
const selectedGenre = ref(null)
const onGenreSelect = (value) => {
  if (!value) return
  const title = genreOptions.value[value]
  const genres = form.value.genres
  const index = genres.indexOf(title)
  index === -1
    ? genres.push(title)
    : genres.splice(index, 1)
  selectedGenre.value = null
}
const onCoverChange = (event) => {
  const file = event.target.files[0]
  if (!file) return
  form.value.coverFile = file
  form.value.coverUrl = ''
  form.value.cover = URL.createObjectURL(file)
}
const onSubmit = () => emit('submit')
const titleInput = ref(null)

defineExpose({
  focusTitle() {
    titleInput.value?.focusInput?.()
  }
})
const sendTitleToServer = debounce((value) => {
  console.log(`Отправили "${value}" на сервер`)
}, 500)
watch(
  () => form.value.title,
  (newTitle) => {
    if (newTitle) sendTitleToServer(newTitle)
  }
)
</script>

<style scoped lang="scss">
  .addForm {
    background-color: #fff;
    width: 500px;
    padding: 30px;

    &_buttonPosition {
      margin-top: 30px;
      display: flex;
      justify-content: center;
      gap: 20px;
    }
  }
  .selectedGenres {
    margin-top: 8px;
    font-size: 14px;
  }
</style>
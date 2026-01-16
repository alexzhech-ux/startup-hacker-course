<template>
  <form class="addForm" @click.stop>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Введите название книги</label>
      <input ref="titleInput" class="addForm_text" type="text" placeholder="Старик и море" :value="modelValue.title" @input="updateField('title', $event.target.value)"/>
    </div>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Введите описание книги</label>
      <textarea class="addForm_text addForm_textarea" placeholder="Это рассказ о..." :value="modelValue.description" @input="updateField('description', $event.target.value)"/>
    </div>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Выберите обложку книги</label>
      <input type="file" accept="image/*" :disabled="!!modelValue.coverUrl" @change="onCoverChange"/>
    </div>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Или введите путь к обложке</label>
      <input class="addForm_text" type="text" placeholder="http://..." :value="modelValue.coverUrl" :disabled="!!modelValue.coverFile" @input="updateField('coverUrl', $event.target.value)"/>
    </div>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Введите описание обложки</label>
      <input class="addForm_text" type="text" placeholder="Книга «Старик и море»" :value="modelValue.coverAlt" @input="updateField('coverAlt', $event.target.value)"/>
    </div>
    <div class="addForm_noFlexFieldContainer">
      <label class="addForm_label">Выберите жанры книги</label>
      <select @change="onGenreSelect">
        <option disabled value="">Выберите жанр</option>
        <option v-for="option in options" :key="option.value" :value="option.text">
          {{ option.text }}
        </option>
      </select>
      <p class="selectedGenres" v-if="modelValue.genres.length">Выбрано:
        <span v-for="(g, i) in modelValue.genres" :key="i">
          {{ g }}<span v-if="i < modelValue.genres.length - 1">, </span>
        </span>
      </p>
    </div>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Книга 18+</label>
      <input type="checkbox" :checked="modelValue.isForAdult" @change="updateField('isForAdult', $event.target.checked)"/>
    </div>
    <div class="addForm_buttonPosition">
      <button type="button" class="addForm_button" @click="onSubmit">{{ isEditMode ? 'Редактировать' : 'Добавить' }}</button>
      <button type="button" class="addForm_cencelButton" @click="emit('cancel')">Отмена</button>
    </div>
  </form>
</template>

<script setup>
  import { ref, watch} from 'vue'
  import debounce from 'lodash/debounce'
  const titleInput = ref(null)
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
  const sendTitleToServer = debounce((value) => {
    console.log(`Отправили "${value}" на сервер`)
  }, 500)
  const emit = defineEmits([
    'update:modelValue',
    'submit',
    'cancel'
  ])
  const updateField = (field, value) => {
    emit('update:modelValue', {
      ...props.modelValue,
      [field]: value
    })
  }
  const onCoverChange = (event) => {
    const file = event.target.files[0]
    if (!file) return

    emit('update:modelValue', {
      ...props.modelValue,
      coverFile: file,
      coverUrl: '',
      cover: URL.createObjectURL(file)
    })
  }
  const onSubmit = () => {
    emit('submit')
  }
  const onGenreSelect = (event) => {
    const genre = event.target.value
    if (!genre) return
    const genres = [...props.modelValue.genres]
    const index = genres.indexOf(genre)
    if (index === -1) {
      genres.push(genre)
    } else {
      genres.splice(index, 1)
    }
    emit('update:modelValue', {
      ...props.modelValue,
      genres
    })
    event.target.value = ''
  }
  defineExpose({
    focusTitle() {
      titleInput.value?.focus()
    }
  })
  watch(
    () => props.modelValue.title,
    (newTitle) => {
      if (!newTitle) return
      sendTitleToServer(newTitle)
    }
  )
</script>

<style scoped lang="scss">
  .addForm {
    background-color: #fff;
    width: 500px;
    padding: 30px;
    &_fieldContainer {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }
    &_noFlexFieldContainer {
      margin-top: 20px;
      display: block;
    }
    &_label {
      margin-right: 4px;
    }
    &_text {
      min-width: 400px;
    }
    &_textarea {
      height: 100px;
    }
    &_buttonPosition {
      margin-top: 30px;
      display: flex;
      justify-content: center;
    }
    &_button,
    &_cencelButton {
      border-radius: 30px;
      padding: 10px 20px;
      font-size: 16px;
    }
    &_cencelButton {
      margin-left: 20px;
    }
  }
</style>
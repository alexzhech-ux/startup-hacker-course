<template>
  <form class="addForm" @click.stop>
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Введите название книги</label>
      <input class="addForm_text" type="text" placeholder="Старик и море" :value="modelValue.title" @input="updateField('title', $event.target.value)"/>
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
    <div class="addForm_fieldContainer">
      <label class="addForm_label">Выберите жанр книги</label>
      <select :value="modelValue.genre" @change="updateField('genre', $event.target.value)">
        <option v-for="option in options" :key="option.value" :value="option.text">{{ option.text }}</option>
      </select>
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
</script>
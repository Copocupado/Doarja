<template>
  <v-file-input
    :class="class"
    :color="color"
    :label="label"
    :model-value="modelValue"
    :rules="rules"
    :variant="variant"
    @update:modelValue="updateValue"
  />
</template>

<script lang="ts" setup>
  import { defineEmits, defineProps } from 'vue'
  const props = defineProps({
    modelValue: {
      type: [File, null],
      required: true,
    },
    class: {
      type: String,
      default: 'text-primary',
    },
    color: {
      type: String,
      default: 'primary',
    },
    label: {
      type: String,
      default: '',
    },
    rules: {
      type: Array,
      default: () => [],
    },
    variant: {
      type: String,
      default: 'outlined',
    },
  })

  const emit = defineEmits(['update-model-value'])

  function updateValue (newValue: InputEvent) {
    emit('update-model-value', newValue)
  }
</script>

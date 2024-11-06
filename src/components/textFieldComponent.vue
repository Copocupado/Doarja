<template>
  <v-text-field
    :append-inner-icon="isPassword ? (show ? 'mdi-eye' : 'mdi-eye-off') : ''"
    :class="class"
    :hint="hint"
    clearable
    :color="color"
    :label="label"
    :model-value="modelValue"
    :placeholder="placeholder"
    :prefix="prefix"
    :rules="rules"
    :disabled="disabled"
    :type="isPassword && !show ? 'password' : 'text'"
    :variant="variant"
    @click:append-inner="togglePasswordVisibility"
    @update:modelValue="updateValue"
  >
    <template #prepend-inner>
      <v-icon :color="color">{{ icon }}</v-icon>
    </template>
  </v-text-field>
</template>

<script lang="ts" setup>
  import { defineEmits, defineProps, ref } from 'vue'

  const show = ref(false) // Toggles password visibility

  const props = defineProps({
    modelValue: {
      type: String,
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
    placeholder: {
      type: String,
      default: '',
    },
    icon: {
      type: String,
      default: 'mdi-hand-heart',
    },
    rules: {
      type: Array,
      default: () => [],
    },
    type: {
      type: String,
      default: 'text',
    },
    variant: {
      type: String,
      default: 'outlined',
    },
    isPassword: {
      type: Boolean,
      default: false,
    },
    prefix:{
      type: String,
      default: '',
    },
    hint: {
      type:String,
      default:''
    },
    disabled: {
      type: Boolean,
      default: false
    }
  })

  const emit = defineEmits(['update-model-value'])

  function updateValue (newValue: InputEvent) {
    emit('update-model-value', newValue)
  }

  function togglePasswordVisibility () {
    if (props.isPassword) {
      show.value = !show.value
    }
  }
</script>

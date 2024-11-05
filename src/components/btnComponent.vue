<template>
  <v-btn
    class="text-background flex-grow-1"
    :color="color"
    :disabled="disabled"
    :loading="loading"
    rounded="lg"
    size="x-large"
    @click="emit('button-clicked')"
  >
    {{ text }}
  </v-btn>
</template>
<script lang="ts" setup>
  const props = defineProps({
    disabled: {
      type: Boolean,
      default: false,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    text: {
      type: String,
      default: '',
    },
    color: {
      type:String,
      default:'secondary'
    }
  })

  const emit = defineEmits(['button-clicked'])
</script>

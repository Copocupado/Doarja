<template>
  <v-btn
    block
    class="text-background bg-secondary"
    :disabled="disabled"
    :loading="loading"
    rounded="lg"
    size="x-large"
    type="submit"
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
  })

  const emit = defineEmits(['button-clicked'])
</script>

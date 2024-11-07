<template>
  <v-container class="d-flex justify-between align-center" style="height: 200px">
    <v-col cols="10">
      <div class="text-h4 font-weight-black text-primary">O que você precisa?</div>
    </v-col>
    <v-col cols="2">
      <v-img src="/src/assets/logo.png" :aspect-ratio="1/1"/>
    </v-col>
  </v-container>
  <SearchComponent />
</template>
<script lang="ts" setup>
import SearchComponent from '@/components/SearchSystem/SearchComponent.vue';

</script>

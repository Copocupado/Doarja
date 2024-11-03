<template>
  <v-navigation-drawer class="bg-primary" expand-on-hover permanent rail>
    <v-list style="height: 6%;">
      <v-list-item
        class="text-background"
        :prepend-avatar="userPfp"
        :subtitle="userSecondaryInfo"
        :title="userMainInfo"
      />
    </v-list>

    <v-divider />

    <v-list nav style="display: flex; flex-direction:column; justify-content: space-between; height:93.8%;">
      <div>
        <v-list-item
          v-for="item in menuItems"
          :key="item.value"
          :class="{
            'text-background': true,
            'v-list-item--active custom-active': selectedSection === item.value,
          }"
          :prepend-icon="item.icon"
          :title="item.title"
          :value="item.value"
          @click="selectSection(item.value)"
        />
      </div>
      <v-list-item
        class="text-background"
        prepend-icon="mdi-logout"
        title="Logout"
        value="Logout"
        @click="logUserOut"
      />
    </v-list>
  </v-navigation-drawer>
</template>

<script lang="ts" setup>
  import { defineEmits, defineProps } from 'vue'
  import { logOut } from '@/models/utility_classes'
  import router from '@/router'

  const prop = defineProps<{
    userPfp: string;
    userMainInfo: string;
    userSecondaryInfo: string;

    selectedSection:string;
  }>()

  const emit = defineEmits(['update-section'])

  const menuItems = [
    { title: 'Administradores', icon: 'mdi-account-hard-hat', value: 'administradores' },
  ]

  // Method to select a section
  const selectSection = (section: string) => {
    // eslint-disable-next-line vue/custom-event-name-casing
    emit('update-section', section)
  }

  const logUserOut = async () => {
    if (await logOut()) {
      console.log('logged user out')
      router.push('/Admin/login')
    } else {
      console.log('error')
    }
  }
</script>

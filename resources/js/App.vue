<script setup>
import { onMounted } from 'vue'

onMounted(async () => {
  await fetch('/sanctum/csrf-cookie', {
    credentials: 'include',
  })
})
</script>

<template>
  <router-view />
</template>
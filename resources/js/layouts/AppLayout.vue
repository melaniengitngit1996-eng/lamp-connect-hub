<script setup>
import Navbar from '../components/Navbar.vue'
</script>

<style scoped>
.app-main {
  padding-top: 84px;
}

@media (min-width: 768px) {
  .app-main {
    padding-top: 0;
  }
}
</style>

<template>
  <div class="min-h-screen flex bg-background">
    <Navbar />

    <main class="flex-1 min-w-0 md:pt-0 app-main">
      <router-view />
    </main>
  </div>
</template>
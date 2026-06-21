<script setup>
  import Navbar from '../components/Navbar.vue'
</script>

<template>
  <div class="min-h-screen flex bg-background">
    <Navbar />
    
    <main class="flex-1 min-w-0 pb-16 md:pb-0">
        <router-view />
    </main>
  </div>
</template>
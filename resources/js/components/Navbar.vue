<script setup>
import { onMounted } from 'vue'
import { useAuth } from '../stores/auth'

const { logout, user, fetchUser } = useAuth()

onMounted(async () => {
    if (!user.value) {
        await fetchUser()
    }
})
</script>

<template>
    <aside class="hidden md:flex w-64 shrink-0 flex-col border-r bg-sidebar text-sidebar-foreground">
      <div class="px-6 py-5 flex items-center gap-2 border-b">
         <div class="h-9 w-9 rounded-xl bg-primary text-primary-foreground grid place-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-5 w-5" aria-hidden="true">
               <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"></path>
            </svg>
         </div>
         <div>
            <div class="font-display text-lg leading-none">LAMP</div>
            <div class="text-xs text-muted-foreground">Church Connect</div>
         </div>
      </div>
      <nav class="flex-1 p-3 space-y-1">
         <RouterLink
               to="/"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition"
               :class="
                  $route.path === '/'
                     ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium'
                     : 'hover:bg-sidebar-accent/60 text-sidebar-foreground/80'
               "
            >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house h-4 w-4" aria-hidden="true">
               <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
               <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            </svg>
            Feed
         </RouterLink>
         <RouterLink
               to="/chat"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition"
               :class="
                  $route.path === '/chat'
                     ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium'
                     : 'hover:bg-sidebar-accent/60 text-sidebar-foreground/80'
               "
            >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-4 w-4" aria-hidden="true">
               <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
            </svg>
            Chat
         </RouterLink>
         <RouterLink
               to="/drive"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition"
               :class="
                  $route.path === '/drive'
                     ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium'
                     : 'hover:bg-sidebar-accent/60 text-sidebar-foreground/80'
               "
            >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-open h-4 w-4" aria-hidden="true">
               <path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"></path>
            </svg>
            Drive
         </RouterLink>
         <RouterLink
               to="/members"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition"
               :class="
                  $route.path === '/members'
                     ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium'
                     : 'hover:bg-sidebar-accent/60 text-sidebar-foreground/80'
               "
            >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4" aria-hidden="true">
               <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
               <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
               <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
               <circle cx="9" cy="7" r="4"></circle>
            </svg>
            Members
         </RouterLink>
      </nav>
      <div class="p-3 border-t flex items-center gap-3">
         <span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9"><img class="aspect-square h-full w-full" src="https://lh3.googleusercontent.com/a/ACg8ocJvyec3OlWtfxjG3D5RG9t3tain8GGu6KW5yqx3Jg5V6uoylENt=s96-c"></span>
         <div class="flex-1 min-w-0">
            <div class="text-sm font-medium truncate">{{ user?.name }}</div>
            <div class="text-xs text-muted-foreground truncate">{{ user?.email }}</div>
         </div>
         <button @click="logout" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-9 w-9" title="Sign out">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out h-4 w-4" aria-hidden="true">
               <path d="m16 17 5-5-5-5"></path>
               <path d="M21 12H9"></path>
               <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            </svg>
         </button>
      </div>
   </aside>
   <div class="md:hidden fixed bottom-0 inset-x-0 z-40 border-t bg-card flex justify-around py-2">
      <a class="flex flex-col items-center gap-1 px-3 py-1 text-xs text-primary active" href="/feed" data-status="active" aria-current="page">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house h-5 w-5" aria-hidden="true">
            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
            <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
         </svg>
         Feed
      </a>
      <a href="/chat" class="flex flex-col items-center gap-1 px-3 py-1 text-xs text-muted-foreground">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square h-5 w-5" aria-hidden="true">
            <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
         </svg>
         Chat
      </a>
      <a href="/drive" class="flex flex-col items-center gap-1 px-3 py-1 text-xs text-muted-foreground">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-open h-5 w-5" aria-hidden="true">
            <path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"></path>
         </svg>
         Drive
      </a>
      <a href="/members" class="flex flex-col items-center gap-1 px-3 py-1 text-xs text-muted-foreground">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-5 w-5" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
            <circle cx="9" cy="7" r="4"></circle>
         </svg>
         Members
      </a>
   </div>
</template>
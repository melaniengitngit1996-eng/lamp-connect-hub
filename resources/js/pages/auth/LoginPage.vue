<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

const login = async () => {
  try {
    loading.value = true
    error.value = ''

    const response = await fetch('/login', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document
          .querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content'),
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.message || 'Login failed')
    }

    // NO localStorage token

    window.location.href = '/'
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}
</script>
<!-- 
<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md rounded-xl border p-6 shadow">
      <h1 class="mb-4 text-2xl font-bold">
        LAMP Church Login
      </h1>

      <form @submit.prevent="login" class="space-y-4">
        <input
          v-model="email"
          class="w-full rounded border p-3"
          type="email"
          placeholder="Email"
        />

        <input
          v-model="password"
          class="w-full rounded border p-3"
          type="password"
          placeholder="Password"
        />

        <p v-if="error" class="text-red-500">
          {{ error }}
        </p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full rounded bg-blue-600 p-3 text-white"
        >
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template> -->

<template>
<div class="min-h-screen grid lg:grid-cols-2">
   <div class="hidden lg:flex flex-col justify-between p-12 bg-primary text-primary-foreground">
      <div class="flex items-center gap-2">
         <div class="h-10 w-10 rounded-xl bg-primary-foreground/15 grid place-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-5 w-5" aria-hidden="true">
               <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"></path>
            </svg>
         </div>
         <div>
            <div class="font-display text-xl leading-none">LAMP</div>
            <div class="text-xs opacity-70">Church Connect</div>
         </div>
      </div>
      <div class="space-y-4 max-w-md">
         <h1 class="font-display text-4xl leading-tight">A quieter place for the community to gather.</h1>
         <p class="text-primary-foreground/70 text-sm leading-relaxed">Share updates, chat with members, and keep important files in one place — private to the LAMP Church family.</p>
      </div>
      <div class="text-xs opacity-60">© LAMP Church</div>
   </div>
   <div class="flex items-center justify-center p-6">
      <div class="w-full max-w-sm">
         <div class="lg:hidden mb-8 flex items-center gap-2">
            <div class="h-9 w-9 rounded-xl bg-primary text-primary-foreground grid place-items-center">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-5 w-5" aria-hidden="true">
                  <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"></path>
               </svg>
            </div>
            <div class="font-display text-lg">LAMP Church Connect</div>
         </div>
         <div dir="ltr" data-orientation="horizontal">
            <!-- <div role="tablist" aria-orientation="horizontal" class="h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground grid grid-cols-2 w-full" tabindex="0" data-orientation="horizontal" style="outline: none;"><button type="button" role="tab" aria-selected="true" aria-controls="radix-_r_3_-content-signin" data-state="active" id="radix-_r_3_-trigger-signin" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background cursor-pointer transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow" tabindex="-1" data-orientation="horizontal" data-radix-collection-item="">Sign in</button><button type="button" role="tab" aria-selected="false" aria-controls="radix-_r_3_-content-signup" data-state="inactive" id="radix-_r_3_-trigger-signup" class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background cursor-pointer transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow" tabindex="-1" data-orientation="horizontal" data-radix-collection-item="">Request access</button></div> -->
            <div data-state="active" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-_r_3_-trigger-signin" id="radix-_r_3_-content-signin" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-4 mt-6" style="animation-duration: 0s;">
               <h2 class="font-display text-2xl">Welcome back</h2>
            </div>
            <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-_r_3_-trigger-signup" hidden="" id="radix-_r_3_-content-signup" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-4 mt-6"></div>
            <form @submit.prevent="login" class="space-y-3 mt-4">
               <div class="space-y-1.5">
                    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">
                        Email
                    </label>
                    <input class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="email" required="" v-model="email" type="email" value="">
                </div>
               <div class="space-y-1.5">
                    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="password">
                        Password
                    </label>
                    <input class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="password" required="" minlength="6" v-model="password" type="password" value="">
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full" :disabled="loading">
                    {{ loading ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>
            <!-- <div class="relative my-5">
               <div class="absolute inset-0 flex items-center"><span class="w-full border-t"></span></div>
               <div class="relative flex justify-center text-xs"><span class="bg-background px-2 text-muted-foreground">or</span></div>
            </div> -->
            <!-- <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full">
               <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24">
                  <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.4-1.6 4.1-5.5 4.1-3.3 0-6-2.7-6-6.1s2.7-6.1 6-6.1c1.9 0 3.1.8 3.8 1.5l2.6-2.5C16.7 3.4 14.5 2.5 12 2.5 6.8 2.5 2.5 6.8 2.5 12S6.8 21.5 12 21.5c6.9 0 9.5-4.8 9.5-7.3 0-.5 0-.9-.1-1.4H12z"></path>
               </svg>
               Continue with Google
            </button> -->
         </div>
      </div>
   </div>
</div>
</template>
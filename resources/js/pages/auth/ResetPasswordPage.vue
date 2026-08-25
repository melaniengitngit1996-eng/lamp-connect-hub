<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const password = ref('')
const passwordConfirmation = ref('')

const loading = ref(false)
const error = ref('')
const errors = ref({})

const submit = async () => {
    loading.value = true
    error.value = ''
    errors.value = {}

    try {
        const response = await fetch('/api/reset-password', {
            method: 'POST',
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content'),
            },
            body: JSON.stringify({
                token: route.params.token,
                email: route.query.email,
                password: password.value,
                password_confirmation:
                    passwordConfirmation.value,
            }),
        })

        const data = await response.json()

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                errors.value = data.errors
                return
            }

            throw new Error(
                data.message || 'Unable to reset password.'
            )
        }

        router.push('/login')
    } catch (e) {
        error.value = e.message
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="min-h-screen grid lg:grid-cols-2">

        <!-- Left panel -->
        <div class="hidden lg:flex flex-col justify-between p-12 bg-primary text-primary-foreground">
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 rounded-xl bg-primary-foreground/15 grid place-items-center">
                    <img src="../../../../public/images/logo.png" alt="LAMP Logo" class="object-contain">
                </div>

                <div>
                    <div class="font-display text-xl leading-none">
                        LAMP
                    </div>

                    <div class="text-xs opacity-70">
                        Church Portal
                    </div>
                </div>
            </div>

            <div class="space-y-4 max-w-md">
                <h1 class="font-display text-4xl leading-tight">
                    A quieter place for the community to gather.
                </h1>

                <p class="text-primary-foreground/70 text-sm leading-relaxed">
                    Share updates, chat with members, and keep important
                    files in one place — private to the LAMP Church family.
                </p>
            </div>

            <div class="text-xs opacity-60">
                © LAMP Church
            </div>
        </div>

        <!-- Reset password form -->
        <div class="flex items-center justify-center p-6">
            <div class="w-full max-w-sm">

                <!-- Mobile logo -->
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <div class="h-9 w-9 rounded-xl bg-primary text-primary-foreground grid place-items-center">
                        <img src="../../../../public/images/logo.png" alt="LAMP Logo" class="object-contain">
                    </div>

                    <div class="font-display text-lg">
                        LAMP Church Portal
                    </div>
                </div>

                <div class="space-y-1.5">
                    <h2 class="font-display text-2xl">
                        Reset your password
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Enter a new password for your account.
                    </p>
                </div>

                <!-- General error -->
                <div v-if="error"
                    class="bg-destructive/10 border border-destructive/20 mt-4 px-3 py-2 rounded-md text-destructive text-sm">
                    {{ error }}
                </div>

                <form @submit.prevent="submit" class="space-y-4 mt-6">

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none" for="password">
                            New password
                        </label>

                        <input id="password" v-model="password" type="password" autocomplete="new-password" required
                            minlength="8"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm">

                        <p v-if="errors.password" class="text-sm text-destructive">
                            {{ errors.password[0] }}
                        </p>
                    </div>

                    <!-- Confirm password -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none" for="password_confirmation">
                            Confirm password
                        </label>

                        <input id="password_confirmation" v-model="passwordConfirmation" type="password"
                            autocomplete="new-password" required minlength="8"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm">

                        <p v-if="errors.password_confirmation" class="text-sm text-destructive">
                            {{ errors.password_confirmation[0] }}
                        </p>
                    </div>

                    <button type="submit" :disabled="loading"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full">
                        {{
                            loading
                                ? 'Resetting Password...'
                                : 'Reset Password'
                        }}
                    </button>
                </form>

                <div class="text-center mt-6">
                    <router-link to="/login" class="text-sm text-primary font-medium hover:underline">
                        Back to sign in
                    </router-link>
                </div>

            </div>
        </div>
    </div>
</template>
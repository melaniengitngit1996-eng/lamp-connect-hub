<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const loading = ref(true)
const submitting = ref(false)

const success = ref(false)

const invitation = ref(null)
const error = ref('')

const errors = ref({})

const form = ref({
    password: '',
    password_confirmation: '',
})

const validate = () => {
    errors.value = {}

    if (!form.value.password) {
        errors.value.password = 'Password is required.'
    }

    if (form.value.password.length < 8) {
        errors.value.password =
            'Password must be at least 8 characters.'
    }

    if (!form.value.password_confirmation) {
        errors.value.password_confirmation =
            'Please confirm your password.'
    }

    if (
        form.value.password &&
        form.value.password_confirmation &&
        form.value.password !==
        form.value.password_confirmation
    ) {
        errors.value.password_confirmation =
            'Passwords do not match.'
    }


    submitting.value = false

    return Object.keys(errors.value).length === 0
}

const loadInvitation = async () => {
    try {
        const response = await fetch(
            `/api/invitations/${route.params.token}`,
            {
                headers: {
                    Accept: 'application/json',
                },
            }
        )

        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message)
        }

        invitation.value = data
    } catch (e) {
        error.value = e.message
    } finally {
        loading.value = false
    }
}

const submit = async () => {
    submitting.value = true
    error.value = ''

    if (!validate()) {
        return
    }

    try {
        const response = await fetch(`/api/invitations/${route.params.token}/signup`, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                password: form.value.password,
                password_confirmation:
                    form.value.password_confirmation,
            }),
        })

        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message)
        }

        success.value = true
    } catch (e) {
        if (e.response?.errors) {
            errors.value = e.response.errors
        }

        error.value = e.message
    } finally {
        submitting.value = false
    }
}

onMounted(loadInvitation)
</script>

<template>
    <div v-if="error" class="flex min-h-screen items-center justify-center bg-background px-4">
        <div class="max-w-md text-center">
            <h1 class="text-7xl font-bold text-foreground">404</h1>
            <h2 class="mt-4 text-xl font-semibold text-foreground">Page not found</h2>
            <p class="mt-2 text-sm text-muted-foreground">{{ error }}</p>
        </div>
    </div>
    <div v-else class="max-w-lg mx-auto px-4 py-8 space-y-6">
        <div class="rounded-xl border bg-card text-card-foreground shadow p-4 space-y-3">
            <div v-if="success" class="text-center py-8 w-full">
                <div class="text-2xl mb-2">
                    🎉
                </div>

                <h3 class="text-xl font-semibold">
                    Account Created Successfully
                </h3>

                <p class="text-muted-foreground mt-2">
                    Your account is awaiting admin approval. You will be notified once your account is approved.
                </p>

                <a
                    href="/login"
                    class="inline-flex mt-4 text-primary hover:underline font-medium"
                >
                    Go to Login
                </a>
            </div>
            <div v-else class="w-full">
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <div class="h-9 w-9 rounded-xl bg-primary text-primary-foreground grid place-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-5 w-5" aria-hidden="true">
                            <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"></path>
                        </svg>
                    </div>
                    <div class="font-display text-lg">LAMP Church Portal</div>
                </div>
                <div class="space-y-1.5 mb-6">
                    <h2 class="font-display text-2xl">Create your account</h2>
                    <p class="text-sm text-muted-foreground">New accounts are reviewed by an admin before access is granted.</p>
                </div>
                <form class="mt-4 space-y-4" @submit.prevent="submit">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="fullName">Full name</label>
                        <input :value="invitation?.full_name" class="bg-muted border-none file:font-medium file:text-foreground h-9 md:text-sm px-3 py-1 rounded-md shadow-sm text-base w-full" id="fullName" readonly maxlength="80">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">Email</label>
                        <input :value="invitation?.email" class="bg-muted border-none file:font-medium file:text-foreground h-9 md:text-sm px-3 py-1 rounded-md shadow-sm text-base w-full" id="email" readonly maxlength="255" type="email">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="localChurch">Local church</label>
                        <input :value="invitation?.local_church" class="bg-muted border-none file:font-medium file:text-foreground h-9 md:text-sm px-3 py-1 rounded-md shadow-sm text-base w-full" id="localChurch" readonly maxlength="120">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="password">Password</label>
                        <input v-model="form.password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="password" autocomplete="new-password" type="password" aria-autocomplete="list">
                        <p
                            v-if="errors?.password"
                            class="text-sm text-destructive"
                        >
                            {{ errors?.password }}
                        </p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="confirmPassword">Confirm password</label>
                        <input v-model="form.password_confirmation" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="confirmPassword" autocomplete="new-password" type="password">
                        <p
                            v-if="errors?.password_confirmation"
                            class="text-sm text-destructive"
                        >
                            {{ errors?.password_confirmation }}
                        </p>
                    </div>
                    <button :disabled="submitting" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full" type="submit">
                        {{
                            submitting
                                ? 'Creating Account...'
                                : 'Create Account'
                        }}
                    </button>
                </form>
                <p class="text-sm text-muted-foreground text-center mt-6">Already have an account? <a href="/login" class="text-primary font-medium hover:underline">Sign in</a></p>
            </div>
        </div>
    </div>
</template>
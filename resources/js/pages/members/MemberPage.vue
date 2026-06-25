<script setup>
import { ref, onMounted, computed } from 'vue'

const users = ref([])
const loading = ref(false)

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'

const pendingUsers = computed(() =>
    users.value.filter(
        user => user.status === 'pending'
    )
)

const loadUsers = async () => {
    loading.value = true

    try {
        const response = await fetch('/api/users')

        users.value = await response.json()
    } finally {
        loading.value = false
    }
}

const approve = async (user) => {
	if (
        !confirm(
            `Approve ${user.name}'s account?`
        )
    ) {
        return
    }

    try {
		const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content')

        const response = await fetch(
            `/api/users/${user.id}/approve`,
            {
                method: 'POST',
                credentials: 'include',
				headers: {
					'Content-Type': 'application/json',
					Accept: 'application/json',
					'X-CSRF-TOKEN': csrfToken,
				},
            }
        )

        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message)
        }

        await loadUsers()
    } catch (e) {
        alert(e.message)
    }
}

const reject = async (user) => {
    if (
        !confirm(
            `Reject ${user.name}'s account?`
        )
    ) {
        return
    }

    try {
		const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content')

        const response = await fetch(
            `/api/users/${user.id}/reject`,
            {
                method: 'POST',
                headers: {
					'Content-Type': 'application/json',
					Accept: 'application/json',
					'X-CSRF-TOKEN': csrfToken,
				},
            }
        )

        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message)
        }

        await loadUsers()
    } catch (e) {
        alert(e.message)
    }
}

const remove = async (user) => {

    if (
        !confirm(
            `Delete ${user.name}?`
        )
    ) {
        return
    }

    try {
		const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content')

        const response = await fetch(
            `/api/users/${user.id}`,
            {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'include',
            }
        )

        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message)
        }

        await loadUsers()

    } catch (e) {
        alert(e.message)
    }
}

onMounted(loadUsers)
</script>

<template>
<div class="max-w-3xl mx-auto px-4 py-8 space-y-8">
	<header>
		<h1 class="font-display text-3xl">Members</h1>
		<p class="text-sm text-muted-foreground">Review and approve new sign-ups.</p>
	</header>
	<section class="space-y-3">
		<h2 class="text-sm font-medium uppercase text-muted-foreground tracking-wide">Pending ({{ pendingUsers.length }})</h2>
		<!-- <div class="rounded-xl border bg-card shadow p-6 text-sm text-center text-muted-foreground">No pending requests.</div> -->
		 <div v-if="!pendingUsers.length" class="rounded-xl border bg-card shadow p-6 text-sm text-center text-muted-foreground">
			No pending requests.
		</div>

		<div v-else class="rounded-xl border bg-card divide-y">
			<div 
				v-for="user in pendingUsers"
				:key="user.id"
				class="flex items-center gap-3 px-4 py-3"
			>
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
					<span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ user.initials }}</span>
				</span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">{{ user.name }}</div>
					<div class="text-xs text-muted-foreground truncate">{{ user.email }}</div>
				</div>
				<Button type="primary"
					@click="approve(user)"
				>
					Approve
				</Button>

				<Button type="danger"
					@click="reject(user)"
				>
					Reject
				</button>
			</div>
		</div>
	</section>
	<section class="space-y-3">
		<h2 class="text-sm font-medium uppercase text-muted-foreground tracking-wide">All members ({{ users.length }})</h2>
		<div class="rounded-xl border bg-card text-card-foreground shadow divide-y">
			<div v-for="user in users" :key="user.id" class="flex items-center gap-3 px-4 py-3">
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
					<span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ user.initials }}</span>
				</span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">{{ user.name }}</div>
					<div class="text-xs text-muted-foreground truncate">{{ user.email }} · joined {{ user.joined_ago }}</div>
				</div>
				<div
					class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors"
					:class="{
						'border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80':
							user.status === 'approved',

						'border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80':
							user.status === 'rejected',

						'border-input bg-muted text-muted-foreground':
							user.status === 'pending',
					}"
				>
					{{ user.status }}
				</div>
				<Button type="icon" @click="remove(user)">
					<TrashIcon />
				</button>
			</div>
		</div>
	</section>
</div>
</template>
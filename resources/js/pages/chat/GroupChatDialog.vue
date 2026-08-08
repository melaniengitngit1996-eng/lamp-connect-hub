<script setup>
import { computed, ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
})

const emit = defineEmits([
    'close',
    'created',
])

const loading = ref(false)
const users = ref([])

const name = ref('')
const search = ref('')
const selectedUsers = ref([])

watch(
    () => props.open,
    async (open) => {
        if (!open) {
            name.value = ''
            search.value = ''
            selectedUsers.value = []
            users.value = []
            return
        }

        loading.value = true

        try {
            const { data } = await axios.get('/api/chat/users')

            users.value = data
        } finally {
            loading.value = false
        }
    }
)

const filteredUsers = computed(() => {

    if (!search.value) {
        return users.value
    }

    return users.value.filter(user =>
        user.name
            .toLowerCase()
            .includes(search.value.toLowerCase())
    )

})

const createGroup = async () => {

    if (!selectedUsers.value.length) {
        return
    }

    loading.value = true

    try {

        const { data } = await axios.post('/api/chat/groups', {
            name: name.value,
            members: selectedUsers.value,
        })

        emit('created', data.data)
        emit('close')

    } finally {
        loading.value = false
    }

}
</script>

<template>
    <Dialog
        :open="open"
        title="New group chat"
        @close="emit('close')"
    >
        <div class="space-y-3">
            <div class="space-y-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="gname">Group name (optional)</label>
                <input class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="gname" placeholder="e.g. Worship team" v-model="name">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Add members</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2.5 top-2.5 h-3.5 w-3.5 text-muted-foreground" aria-hidden="true">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search members" v-model="search">
                </div>
            </div>
            <div class="max-h-64 overflow-y-auto border rounded-md divide-y">
                <label
                    v-for="member in filteredUsers"
                    :key="member.id"
                    class="flex items-center gap-3 px-3 py-2 hover:bg-accent cursor-pointer"
                >
                    <input
                        v-model="selectedUsers"
                        :value="member.id"
                        type="checkbox"
                    >

                    <span
                        class="relative flex h-7 w-7 shrink-0 overflow-hidden rounded-full"
                    >
                        <span
                            class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px]"
                        >
                            {{ member.initials }}
                        </span>
                    </span>

                    <span class="flex-1 text-sm">
                        {{ member.name }}
                    </span>
                </label>

                <div
                    v-if="!filteredUsers.length"
                    class="p-6 text-center text-sm text-muted-foreground"
                >
                    No members found.
                </div>

            </div>
        </div>
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
            <Button type="plain" @click="emit('close')">Cancel</Button>
            <Button
                type="primary"
                :disabled="!selectedUsers.length || loading"
                @click="createGroup"
            >
                Create Group
            </Button>
        </div>
    </Dialog>
</template>

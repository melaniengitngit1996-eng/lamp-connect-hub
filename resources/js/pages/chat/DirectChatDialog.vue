<script setup>
import { ref, watch, computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits([
    'close',
    'created',
])

const loading = ref(false)
const members = ref([])
const selectedUser = ref(null)
const search = ref('')

watch(
    () => props.open,
    async (open) => {
        if (!open) {
            members.value = []
            selectedUser.value = null
            search.value = ''
            return
        }

        loading.value = true

        try {
            const { data } = await axios.get('/api/chat/users')

            members.value = data
        } finally {
            loading.value = false
        }
    }
)

const filteredMembers = computed(() => {

    if (!search.value) {
        return members.value
    }

    return members.value.filter(member =>
        member.name
            .toLowerCase()
            .includes(search.value.toLowerCase())
    )
})

const createConversation = async () => {

    if (!selectedUser.value) {
        return
    }

    loading.value = true

    try {

        const { data } = await axios.post('/api/chat/direct', {
            user_id: selectedUser.value,
        })

        emit('created', data)
        emit('close')

    } finally {
        loading.value = false
    }
}
</script>

<template>
    <Dialog
        :open="open"
        title="New direct message"
        @close="emit('close')"
    >
        <div class="space-y-3">
            <div class="space-y-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Choose a person</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2.5 top-2.5 h-3.5 w-3.5 text-muted-foreground" aria-hidden="true">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input v-model="search" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search members" value="">
                </div>
            </div>
            <div class="max-h-64 overflow-y-auto border rounded-md divide-y">
                <label
                    v-for="member in filteredMembers"
                    :key="member.id"
                    class="flex items-center gap-3 px-3 py-2 hover:bg-accent cursor-pointer"
                >
                    <input
                        v-model="selectedUser"
                        :value="member.id"
                        type="radio"
                        class="h-4 w-4"
                    >

                    <span class="relative flex h-7 w-7 shrink-0 overflow-hidden rounded-full">
                        <span
                            class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px]"
                        >
                            {{ member.initials }}
                        </span>
                    </span>

                    <span class="text-sm flex-1">
                        {{ member.name }}
                    </span>
                </label>

                <div
                    v-if="!filteredMembers.length"
                    class="p-6 text-center text-sm text-muted-foreground"
                >
                    No members found.
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <Button
                type="plain"
                @click="emit('close')"
            >
                Cancel
            </Button>

            <Button
                type="primary"
                :disabled="!selectedUser || loading"
                @click="createConversation"
            >
                Start Chat
            </Button>
        </div>
    </Dialog>
</template>

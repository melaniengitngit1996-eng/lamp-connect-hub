<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useAuth } from '../../stores/auth'
const { user } = useAuth()

import UserMinusIcon from '../../icons/UserMinusIcon.vue'
import UserPlusIcon from '../../icons/UserPlusIcon.vue'
import SearchIcon from '../../icons/SearchIcon.vue'
import CheckIcon from '../../icons/CheckIcon.vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    conversation: Object,
    members: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits([
    'close',
    'member-removed',
])

const showAddMember = ref(false)
const search = ref('')
const users = ref([])
const loading = ref(false)
let timeout = null
const selectedMembers = ref([])

watch(search, (value) => {

    clearTimeout(timeout)

    if (!value.trim()) {
        users.value = []
        return
    }

    timeout = setTimeout(async () => {

        loading.value = true

        try {

            const { data } = await axios.get(
                `/api/chat/conversations/${props.conversation.id}/available-users`,
                {
                    params: {
                        search: value,
                    },
                }
            )

            users.value = data.data ?? data

        } finally {
            loading.value = false
        }

    }, 300)

})

const addMembers = async () => {

    loading.value = true

    try {

        await axios.post(
            `/api/chat/conversations/${props.conversation.id}/members`,
            {
                user_ids: selectedMembers.value.map(
                    member => member.id
                ),
            }
        )

        emit('member-added')

        showAddMember.value = false
        search.value = ''
        users.value = []
        selectedMembers.value = []

    } finally {
        loading.value = false
    }
}

const removeMember = async (member) => {

    if (!confirm(`Remove ${member.name} from this group?`)) {
        return
    }

    await axios.delete(
        `/api/chat/conversations/${props.conversation.id}/members/${member.id}`
    )

    emit('member-removed')
}

const leaveConversation = async () => {

    if (!confirm('Leave this group?')) {
        return
    }

    await axios.delete(
        `/api/chat/conversations/${props.conversation.id}/members/${user.id}`
    )

    emit('close')
}

const toggleMember = (member) => {
    const index = selectedMembers.value.findIndex(
        item => item.id === member.id
    )

    if (index === -1) {
        selectedMembers.value.push(member)
    } else {
        selectedMembers.value.splice(index, 1)
    }
}

const isSelected = (member) => {
    return selectedMembers.value.some(
        item => item.id === member.id
    )
}

watch(
    () => props.open,
    (open) => {
        showAddMember.value = false
        search.value = ''
        users.value = []
        selectedMembers.value = []
    }
)
</script>

<template>
    <Dialog
        :open="open"
        title="Members"
        :description="`${conversation?.name} · ${members.length} members`"
        @close="emit('close')"
    > 
        <div>
            <button
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs"
                @click="showAddMember = true"
            >
                <UserPlusIcon />
                Add members
            </button>
        </div>
        <div v-if="showAddMember">
            <div class="rounded-lg border p-3 space-y-3" data-tsd-source="/src/components/chat-members-dialog.tsx:159:15">
                <div class="relative" data-tsd-source="/src/components/chat-members-dialog.tsx:160:17">
                        <SearchIcon />
                        <input
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8"
                        placeholder="Search members…"
                        v-model="search"
                    />
                </div>
                <div v-if="search" class="max-h-48 overflow-y-auto divide-y">
                    <button
                        v-for="member in users"
                        :key="member.id"
                        type="button"
                        @click="toggleMember(member)"
                        :class="[
                            'w-full flex items-center gap-3 py-2 px-2 rounded text-left transition',
                            isSelected(member)
                                ? 'bg-accent'
                                : 'hover:bg-muted/50'
                        ]"
                    >
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-full bg-muted"
                            >
                                {{ member.initials }}
                            </span>
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm">
                                {{ member.name }}
                            </div>
                        </div>

                        <CheckIcon
                            v-if="isSelected(member)"
                            class="h-4 w-4 text-primary"
                        />
                    </button>
                </div>
                <div
                    v-if="!loading && !users.length"
                    class="p-6 text-center text-sm text-muted-foreground"
                >
                    No members found.
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="
                        showAddMember = false;
                        search = '';
                        users = [];
                        selectedMembers = [];
                    " class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
                        Cancel
                    </button>
                    <button
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs"
                        :disabled="!selectedMembers.length"
                        @click="addMembers"
                    >
                        Add <span v-if="selectedMembers.length">({{ selectedMembers.length }})</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="max-h-80 overflow-y-auto divide-y">
            <div
                v-for="member in members"
                :key="member.id"
                class="flex items-center gap-3 py-2.5"
            >
                <span class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-full">

                    <img
                        v-if="member.avatar"
                        :src="member.avatar"
                        class="aspect-square h-full w-full"
                    />

                    <span
                        v-else
                        class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs"
                    >
                        {{ member.initials }}
                    </span>

                </span>

                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-medium">
                        {{ member.name }}

                        <span
                            v-if="member.id === user.id"
                            class="font-normal text-muted-foreground"
                        >
                            (you)
                        </span>

                        <span
                            v-if="member.pivot.role === 'owner'"
                            class="ml-2 rounded bg-muted px-1.5 py-0.5 text-[10px]"
                        >
                            Owner
                        </span>
                    </div>
                </div>

                <!-- Leave -->
                <button
                    v-if="member.id === user.id && member.pivot.role != 'owner'"
                    @click="leaveConversation"
                    class="inline-flex h-8 items-center justify-center gap-2 rounded-md px-3 text-xs font-medium text-destructive transition-colors hover:bg-accent hover:text-destructive"
                >
                    <UserMinusIcon class="h-4 w-4" />
                    <span>Leave</span>
                </button>

                <!-- Remove -->
                <button
                    v-else-if="member.id != user.id && member.pivot.role != 'owner'"
                    @click="removeMember(member)"
                    class="inline-flex h-8 items-center justify-center gap-2 rounded-md px-3 text-xs font-medium text-destructive transition-colors hover:bg-accent hover:text-destructive"
                >
                    <UserMinusIcon class="h-4 w-4" />
                    <span>Remove</span>
                </button>

            </div>

        </div>
    </Dialog>
</template>
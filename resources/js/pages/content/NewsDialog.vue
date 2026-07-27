<script setup>
import { ref, watch, computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    announcement: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits([
    'close',
    'saved',
])

const errors = ref({})
const saving = ref(false)

const defaultForm = () => ({
    title: '',
    content: '',
    venue: '',
    status: 'draft',
    is_pinned: false,
    published_at: '',
})

const form = ref(defaultForm())

const isEditing = computed(() => !!props.announcement)

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        if (!props.announcement) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            title: props.announcement.title,
            content: props.announcement.content,
            venue: props.announcement.venue,
            status: props.announcement.status,
            is_pinned: props.announcement.is_pinned,
            published_at: props.announcement.published_at
                ? props.announcement.published_at.substring(0, 16)
                : '',
        })
    },
    { immediate: true }
)

const save = async () => {
    saving.value = true
    errors.value = {}

    try {
        if (props.announcement) {
            await axios.put(
                `/api/announcements/${props.announcement.id}`,
                form.value
            )
        } else {
            await axios.post(
                '/api/announcements',
                form.value
            )
        }

        emit('saved')
        emit('close')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
            return
        }

        throw error
    } finally {
        saving.value = false
    }
}
</script>

<template>
    <Dialog
        :open="open"
        :title="isEditing ? 'Edit Announcement' : 'New Announcement'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Title</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.title">
                <p
                    v-if="errors.title"
                    class="mt-1 text-sm text-destructive"
                >
                    {{ errors.title[0] }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">content</label>
                <textarea v-model="form.content" class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" rows="3" placeholder="Optional" spellcheck="false"></textarea>
                <p
                    v-if="errors.content"
                    class="mt-1 text-sm text-destructive"
                >
                    {{ errors.content[0] }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Status</label>
                <select
                    v-model="form.status"
                    class="mt-2 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                    <option :value="null" disabled>
                        Select status
                    </option>

                    <option value="draft">draft</option>
                    <option value="published">published</option>
                    <option value="cancelled">cancelled</option>
                    <option value="archived">archived</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium leading-none">
                    Published At
                </label>

                <input
                    type="datetime-local"
                    v-model="form.published_at"
                    class="mt-2 flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                <p
                    v-if="errors.published_at"
                    class="mt-1 text-sm text-destructive"
                >
                    {{ errors.published_at[0] }}
                </p>
            </div>

            <div class="flex items-center justify-between rounded-md border p-3">
                <div>
                    <label class="text-sm font-medium">
                        Pin Announcement
                    </label>

                    <p class="text-xs text-muted-foreground">
                        Pinned announcements appear first.
                    </p>
                </div>

                <input
                    type="checkbox"
                    v-model="form.is_pinned"
                    class="h-4 w-4 rounded border-input"
                >
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                <Button
                    type="plain"
                    @click="emit('close')"
                >
                    Cancel
                </Button>

                <Button
                    type="primary"
                    :disabled="saving"
                    @click="save"
                >
                    {{ props.announcement ? 'Save Changes' : 'Create Content' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>
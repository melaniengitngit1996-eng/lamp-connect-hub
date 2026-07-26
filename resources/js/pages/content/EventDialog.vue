<script setup>
import { ref, watch, computed, onBeforeUnmount } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    event: {
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
const imagePreview = ref(null)
const defaultForm = () => ({
    title: '',
    description: '',
    venue: '',
    status: 'draft',
    cover_image: null,
})

const onImageSelected = (event) => {
    const file = event.target.files?.[0]

    if (!file) return

    form.value.cover_image = file

    if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value)
    }

    imagePreview.value = URL.createObjectURL(file)
}

const form = ref(defaultForm())

const isEditing = computed(() => !!props.event)

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        if (!props.event) {
            Object.assign(form.value, defaultForm())
            imagePreview.value = null
            return
        }

        Object.assign(form.value, {
            title: props.event.title,
            description: props.event.description,
            venue: props.event.venue,
            status: props.event.status,
            cover_image: null,
        })

        imagePreview.value = props.event.cover_image
    },
    { immediate: true }
)

const save = async () => {
    saving.value = true
    errors.value = {}

    const payload = new FormData()

    payload.append('title', form.value.title)
    payload.append('description', form.value.description ?? '')
    payload.append('venue', form.value.venue ?? '')
    payload.append('status', form.value.status)

    if (form.value.cover_image instanceof File) {
        payload.append('cover_image', form.value.cover_image)
    }

    try {
        if (props.event) {
            payload.append('_method', 'PUT')

            await axios.post(
                `/api/events/${props.event.id}`,
                payload,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }
            )
        } else {
            await axios.post(
                '/api/events',
                payload,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }
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

onBeforeUnmount(() => {
    if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value)
    }
})
</script>

<template>
    <Dialog
        :open="open"
        :title="isEditing ? 'Edit Content' : 'New Content'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Title</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.title">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Description</label>
                <textarea v-model="form.description" class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" rows="3" placeholder="Optional" spellcheck="false"></textarea>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Venue</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.venue">
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
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" data-tsd-source="/src/routes/_app.admin.content.tsx:302:15">Cover image </label>
                <label
                    class="mt-2 flex flex-col items-center justify-center border border-dashed rounded-lg overflow-hidden cursor-pointer hover:bg-accent transition"
                >
                    <template v-if="imagePreview">
                        <img
                            :src="imagePreview"
                            class="w-full h-40 object-cover"
                        />
                    </template>

                    <template v-else>
                        <div class="h-40 flex flex-col items-center justify-center gap-2 text-sm text-muted-foreground" style="min-height: 200px;">
                            Click to upload an image
                        </div>
                    </template>

                    <input
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="onImageSelected"
                    >
                </label>
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
                    {{ props.event ? 'Save Changes' : 'Create Content' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>
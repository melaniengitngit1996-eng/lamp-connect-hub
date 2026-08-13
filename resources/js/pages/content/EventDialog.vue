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

const form = ref({
    title: '',
    description: '',
    venue: '',
    status: 'draft',
    photos: [],
})

const existingPhotos = ref([])
const imagePreviews = ref([])
const deletedImageIds = ref([])

const defaultForm = () => ({
    title: '',
    description: '',
    venue: '',
    status: 'draft',
    photos: [],
})

const isEditing = computed(() => !!props.event)


// ---------------------------------------------------------
// Image helpers
// ---------------------------------------------------------

const revokePreviewUrls = () => {
    imagePreviews.value.forEach((preview) => {
        if (preview.url?.startsWith('blob:')) {
            URL.revokeObjectURL(preview.url)
        }
    })
}

const onImagesSelected = (event) => {
    const files = Array.from(event.target.files || [])

    if (!files.length) return

    const newFiles = files.map((file) => ({
        file,
        url: URL.createObjectURL(file),
    }))

    form.value.photos.push(...files)

    imagePreviews.value.push(...newFiles)

    // Reset input so selecting the same file again works
    event.target.value = ''
}

const removeNewImage = (index) => {
    const preview = imagePreviews.value[index]

    if (preview?.url?.startsWith('blob:')) {
        URL.revokeObjectURL(preview.url)
    }

    imagePreviews.value.splice(index, 1)
    form.value.photos.splice(index, 1)
}

const removeExistingImage = (index) => {
    const photo = existingPhotos.value[index]

    if (photo?.id) {
        deletedImageIds.value.push(photo.id)
    }

    existingPhotos.value.splice(index, 1)
}


// ---------------------------------------------------------
// Form
// ---------------------------------------------------------

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        revokePreviewUrls()

        imagePreviews.value = []
        existingPhotos.value = []
        deletedImageIds.value = []

        if (!props.event) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            title: props.event.title ?? '',
            description: props.event.description ?? '',
            venue: props.event.venue ?? '',
            status: props.event.status ?? 'draft',
            photos: [],
        })

        /*
         * Existing photos from API
         */
        existingPhotos.value = (props.event.images ?? []).map((photo) => ({
            id: photo.id,
            url: photo.url,
            is_cover: photo.is_cover,
        }))
    },
    { immediate: true }
)


// ---------------------------------------------------------
// Save
// ---------------------------------------------------------

const save = async () => {
    saving.value = true
    errors.value = {}

    const payload = new FormData()

    payload.append(
        'title',
        form.value.title
    )

    payload.append(
        'description',
        form.value.description ?? ''
    )

    payload.append(
        'venue',
        form.value.venue ?? ''
    )

    payload.append(
        'status',
        form.value.status
    )

    /*
     * New photos
     */
    form.value.photos.forEach((file) => {
        payload.append('photos[]', file)
    })

    /*
     * Existing photos removed by the user
     */
    deletedImageIds.value.forEach((id) => {
        payload.append('delete_images[]', id)
    })

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
            errors.value = error.response.data.errors ?? {}
            return
        }

        throw error
    } finally {
        saving.value = false
    }
}


// ---------------------------------------------------------
// Cleanup
// ---------------------------------------------------------

onBeforeUnmount(() => {
    revokePreviewUrls()
})
</script>


<template>
    <Dialog :open="open" :title="isEditing ? 'Edit Content' : 'New Content'" @close="emit('close')">
        <div class="space-y-4">

            <!-- Title -->
            <div>
                <label class="text-sm font-medium leading-none">
                    Title
                </label>

                <input v-model="form.title"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm">

                <p v-if="errors.title" class="mt-1 text-sm text-destructive">
                    {{ errors.title[0] }}
                </p>
            </div>


            <!-- Description -->
            <div>
                <label class="text-sm font-medium leading-none">
                    Description
                </label>

                <textarea v-model="form.description" rows="3" placeholder="Optional" spellcheck="false"
                    class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm" />

                <p v-if="errors.description" class="mt-1 text-sm text-destructive">
                    {{ errors.description[0] }}
                </p>
            </div>


            <!-- Venue -->
            <div>
                <label class="text-sm font-medium leading-none">
                    Venue
                </label>

                <input v-model="form.venue"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm">

                <p v-if="errors.venue" class="mt-1 text-sm text-destructive">
                    {{ errors.venue[0] }}
                </p>
            </div>


            <!-- Status -->
            <div>
                <label class="text-sm font-medium leading-none">
                    Status
                </label>

                <select v-model="form.status"
                    class="mt-2 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                    <option :value="null" disabled>
                        Select status
                    </option>

                    <option value="draft">
                        draft
                    </option>

                    <option value="published">
                        published
                    </option>

                    <option value="cancelled">
                        cancelled
                    </option>

                    <option value="archived">
                        archived
                    </option>
                </select>

                <p v-if="errors.status" class="mt-1 text-sm text-destructive">
                    {{ errors.status[0] }}
                </p>
            </div>


            <!-- Photos -->
            <div>
                <label class="text-sm font-medium leading-none">
                    Photos
                </label>

                <!-- Existing + New Photos -->
                <div v-if="existingPhotos.length || imagePreviews.length" class="mt-2 grid grid-cols-3 gap-2">

                    <!-- Existing Photos -->
                    <div v-for="(photo, index) in existingPhotos" :key="`existing-${photo.id}`"
                        class="relative aspect-[4/3] overflow-hidden rounded-lg border bg-muted">
                        <img :src="photo.url" alt="" class="h-full w-full object-cover">

                        <!-- Cover -->
                        <span v-if="index === 0"
                            class="absolute bottom-1 left-1 rounded bg-background/80 px-1.5 py-0.5 text-[10px]">
                            Cover
                        </span>

                        <!-- Remove -->
                        <button type="button"
                            class="absolute right-1 top-1 inline-flex h-6 w-6 items-center justify-center rounded-md bg-secondary text-secondary-foreground shadow-sm transition-colors hover:bg-secondary/80"
                            @click="removeExistingImage(index)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-3 w-3">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>


                    <!-- New Photos -->
                    <div v-for="(preview, index) in imagePreviews" :key="preview.url"
                        class="relative aspect-[4/3] overflow-hidden rounded-lg border bg-muted">
                        <img :src="preview.url" alt="" class="h-full w-full object-cover">

                        <!-- Cover -->
                        <span v-if="
                            existingPhotos.length === 0 &&
                            index === 0
                        " class="absolute bottom-1 left-1 rounded bg-background/80 px-1.5 py-0.5 text-[10px]">
                            Cover
                        </span>

                        <!-- Remove -->
                        <button type="button"
                            class="absolute right-1 top-1 inline-flex h-6 w-6 items-center justify-center rounded-md bg-secondary text-secondary-foreground shadow-sm transition-colors hover:bg-secondary/80"
                            @click="removeNewImage(index)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-3 w-3">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                </div>


                <!-- Upload -->
                <label
                    class="mt-2 flex h-24 cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed text-sm text-muted-foreground transition hover:bg-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-4 w-4">
                        <path d="M16 5h6" />
                        <path d="M19 2v6" />
                        <path d="M21 11.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7.5" />
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                        <circle cx="9" cy="9" r="2" />
                    </svg>

                    Click to upload photo(s)

                    <input type="file" accept="image/*" multiple class="hidden" @change="onImagesSelected">
                </label>

                <p class="mt-1 text-xs text-muted-foreground">
                    You can select multiple photos.
                </p>

                <p v-if="errors.photos" class="mt-1 text-sm text-destructive">
                    {{ errors.photos[0] }}
                </p>

                <p v-if="errors['photos.0']" class="mt-1 text-sm text-destructive">
                    {{ errors['photos.0'][0] }}
                </p>
            </div>


            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                <Button type="plain" @click="emit('close')">
                    Cancel
                </Button>

                <Button type="primary" :disabled="saving" @click="save">
                    {{ isEditing ? 'Save Changes' : 'Create Content' }}
                </Button>
            </div>

        </div>
    </Dialog>
</template>
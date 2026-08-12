<script setup>
import { ref } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    folderId: {
        type: Number,
        default: null,
    },
})

const emit = defineEmits([
    'close',
    'uploaded',
])

const file = ref(null)
const loading = ref(false)
const fileInput = ref(null)
const errors = ref({})

const handleFileChange = (event) => {
    file.value = event.target.files?.[0] ?? null
    errors.value = {}
}

const uploadFile = async () => {
    if (!file.value) {
        return
    }

    loading.value = true

    try {
        const formData = new FormData()

        formData.append('file', file.value)
        formData.append('folder_id', props.folderId ?? '')

        const response = await fetch('/api/drive/files', {
            method: 'POST',
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.content,
            },
            body: formData,
        })

        if (response.status === 422) {
            const data = await response.json()

            errors.value = data.errors

            return
        }

        if (!response.ok) {
            return
        }

        file.value = null

        if (fileInput.value) {
            fileInput.value.value = ''
        }

        emit('uploaded')
        emit('close')
    } finally {
        loading.value = false
    }
}

const close = () => {
    file.value = null

    if (fileInput.value) {
        fileInput.value = ''
    }

    emit('close')
}
</script>

<template>
    <Dialog :open="open" title="Upload file" @close="close">
        <div class="space-y-4">
            <input ref="fileInput" type="file" @change="handleFileChange" class="block w-full rounded-md border border-input bg-background p-2 text-sm text-muted-foreground
                    file:mr-3
                    file:rounded-md
                    file:border-0
                    file:bg-primary
                    file:px-3
                    file:py-2
                    file:text-sm
                    file:font-medium
                    file:text-primary-foreground
                    file:cursor-pointer
                    hover:file:opacity-90
                    cursor-pointer" />

            <p v-if="errors.file" class="text-sm text-destructive">
                {{ errors.file[0] }}
            </p>

            <div class="flex justify-end gap-2">
                <Button type="plain" @click="close">
                    Cancel
                </Button>

                <Button type="primary" :disabled="!file || loading" @click="uploadFile">
                    {{ loading ? 'Uploading...' : 'Upload' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>
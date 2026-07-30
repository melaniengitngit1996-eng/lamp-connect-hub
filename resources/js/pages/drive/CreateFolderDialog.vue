<script setup>
import { ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    parentId: Number,
})

const emit = defineEmits([
    'close',
    'created',
])

const folderName = ref('')
const loading = ref(false)
const errors = ref({})

watch(() => props.open, (open) => {
    if (open) {
        folderName.value = ''
        errors.value = {}
    }
})

watch(folderName, () => {
    delete errors.value.name
})

const createFolder = async () => {
    if (!folderName.value.trim()) {
        return
    }

    loading.value = true

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content')
            
        const response = await fetch('/api/drive/folders', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                name: folderName.value,
                parent_id: props.parentId,
            }),
        })

        if (response.status === 422) {
            const data = await response.json()

            errors.value = data.errors

            return
        }

        emit('created')
        emit('close')
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <Dialog
        :open="open"
        title="New folder"
        @close="emit('close')"
    >
            <div>
                <input
                    v-model="folderName"
                    placeholder="Folder name"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                    @keyup.enter="createFolder"
                >

                <p
                    v-if="errors.name"
                    class="mt-1 text-sm text-destructive"
                >
                    {{ errors.name[0] }}
                </p>
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
                    :disabled="loading || !folderName.trim()"
                    @click="createFolder"
                >
                    Create
                </Button>
            </div>
    </Dialog>
</template>
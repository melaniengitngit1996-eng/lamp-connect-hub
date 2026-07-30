<script setup>
import { computed, ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    item: Object,
    type: String,
})

const emit = defineEmits([
    'close',
    'created',
    'renamed',
])

const loading = ref(false)
const name = ref('')

watch(
    () => props.open,
    open => {
        if (!open || !props.item) {
            return
        }

        name.value =
            props.type === 'file'
                ? props.item.name
                : props.item.name
    }
)

const save = async () => {
    if (loading.value) {
        return
    }

    loading.value = true

    try {
        const endpoint =
            props.type === 'file'
                ? `/api/drive/files/${props.item.id}`
                : `/api/drive/folders/${props.item.id}`

        const response = await fetch(endpoint, {
            method: 'PATCH',
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                )?.content,
            },
            body: JSON.stringify({
                name: name.value.trim(),
            }),
        })

        if (!response.ok) {
            return
        }

        emit('renamed')
        emit('close')
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <Dialog
        :open="open"
        :title="type === 'folder' ? 'Rename folder' : 'Rename file'"
        @close="emit('close')"
    >
            <input
                v-model="name"
                :placeholder="type === 'folder' ? 'Folder name' : 'File name'"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                @keyup.enter="save"
            >

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                <Button
                    type="plain"
                    @click="emit('close')"
                >
                    Cancel
                </Button>

                <Button
                    type="primary"
                    :disabled="loading || !name.trim()"
                    @click="save"
                >
                    Rename
                </Button>
            </div>
    </Dialog>
</template>
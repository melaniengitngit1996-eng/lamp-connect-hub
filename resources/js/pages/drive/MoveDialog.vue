<script setup>
import { computed, ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'
import MiniFolderIcon from '../../icons/MiniFolderIcon.vue'
import CaretIcon from '../../icons/CaretIcon.vue'
import HomeIcon from '../../icons/HomeIcon.vue'

const emit = defineEmits([
    'close',
    'moved'
])

const props = defineProps({
    open: Boolean,

    item: {
        type: Object,
        required: true,
    },

    type: {
        type: String,
        required: true, // 'file' | 'folder'
    },
})

const folders = ref([])
const destinationFolder = ref(null)
const loading = ref(false)

const breadcrumbs = ref([
    {
        id: null,
        name: 'Drive',
    },
])

const openFolder = async (folder) => {
    console.log(folder);
    destinationFolder.value = folder

    breadcrumbs.value.push({
        id: folder.id,
        name: folder.name,
    })

    await loadFolders(folder.id)
}

const goToBreadcrumb = async (index) => {
    breadcrumbs.value = breadcrumbs.value.slice(0, index + 1)

    destinationFolder.value =
        breadcrumbs.value.at(-1).id
            ? breadcrumbs.value.at(-1)
            : null

    await loadFolders(destinationFolder.value?.id ?? null)
}

const loadFolders = async (parentId = null) => {
    const params = new URLSearchParams({
        folders_only: 1,
    })

    if (parentId !== null) {
        params.append('parent_id', parentId)
    }

    console.log(params.toString())

    const response = await fetch(
        `/api/drive/folders?${params.toString()}`,
        {
            credentials: 'include',
        },
    )

    const data = await response.json()

    console.log(data)

    folders.value = data.folders
}

const visibleFolders = computed(() =>
    folders.value.filter(folder => folder.id !== props.item.id)
)

watch(
    () => props.open,
    async (open) => {
        if (!open) {
            return
        }

        destinationFolder.value = null

        breadcrumbs.value = [
            {
                id: null,
                name: 'Drive',
            },
        ]

        await loadFolders()
    },
)

const canMove = computed(() => {
    const currentParentId =
        props.type === 'folder'
            ? props.item.parent_id
            : props.item.folder_id

    return currentParentId !== (destinationFolder.value?.id ?? null)
})

const destination = computed(() =>
    breadcrumbs.value.map(crumb => crumb.name).join(' / ')
)

const move = async () => {
    loading.value = true

    try {
        const endpoint =
            props.type === 'folder'
                ? `/api/drive/folders/${props.item.id}/move`
                : `/api/drive/files/${props.item.id}/move`

        const payload =
            props.type === 'folder'
                ? {
                    parent_id: destinationFolder.value?.id ?? null,
                }
                : {
                    folder_id: destinationFolder.value?.id ?? null,
                }

        const response = await fetch(endpoint, {
            method: 'PATCH',
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.content,
            },
            body: JSON.stringify(payload),
        })

        if (!response.ok) {
            return
        }

        emit('moved')
        emit('close')
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <Dialog
        :open="open"
        @close="emit('close')"
    >
        <div
            class="flex flex-col space-y-1.5 text-center sm:text-left"
            data-tsd-source="/src/components/drive-move-dialog.tsx:124:9"
        >
            <h2 class="text-lg font-semibold leading-none tracking-tight">
                Move "{{ props.item.name }}"
            </h2>
        </div>
        <div class="flex items-center gap-1 text-sm flex-wrap">
            <template
                v-for="(crumb, index) in breadcrumbs"
                :key="crumb.id ?? 'root'"
            >
                <button
                    class="rounded px-2 py-1 hover:bg-accent font-medium"
                    @click="goToBreadcrumb(index)"
                >
                    <HomeIcon
                        v-if="index === 0"
                        class="h-4 w-4"
                    />
                    {{ crumb.name }}
                </button>

                <span
                    v-if="index < breadcrumbs.length - 1"
                    class="text-muted-foreground"
                >
                    <CaretIcon />
                </span>
            </template>
        </div>
        <div
            v-if="folders.length"
            class="border rounded-md divide-y max-h-64 overflow-y-auto"
        >
            <button
                v-for="folder in visibleFolders"
                :key="folder.id"
                class="w-full flex items-center gap-2 px-3 py-3 text-left hover:bg-accent/40"
                @click="openFolder(folder)"
            >
                <MiniFolderIcon />
                <span class="truncate flex-1" data-tsd-source="/src/components/drive-move-dialog.tsx:169:19">{{ folder.name }}</span>
                <CaretIcon />
            </button>
        </div>
        <div
            v-else
            class="border rounded-md divide-y max-h-64 overflow-y-auto"
        >
            <div
                class="py-8 text-center text-sm text-muted-foreground"
            >
                No subfolders here.
            </div>
        </div>
        <p class="text-xs text-muted-foreground">
            Destination: {{ destination }}
        </p>
        <div
            class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2"
            data-tsd-source="/src/components/drive-move-dialog.tsx:181:9"
        >
            <Button
                type="plain"
                @click="emit('close')"
            >
                Cancel
            </Button>
            <Button
                :disabled="!canMove || loading"
                @click="move"
                type="primary"
            >
                Move here
            </Button>
        </div>

    </Dialog>
</template>
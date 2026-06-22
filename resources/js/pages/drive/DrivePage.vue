<script setup>
import { ref, onMounted } from 'vue'

import Dialog from '../../components/Dialog.vue'
import Button from '../../components/Button.vue'
import FolderPlusIcon from '../../icons/FolderPlusIcon.vue'
import UploadIcon from '../../icons/UploadIcon.vue'
import HomeIcon from '../../icons/HomeIcon.vue'
import FolderOrangeIcon from '../../icons/FolderOrangeIcon.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import CaretIcon from '../../icons/CaretIcon.vue'

import CreateFolderDialog from '../../pages/drive/CreateFolderDialog.vue'
import DeleteFolderDialog from '../../pages/drive/DeleteFolderDialog.vue'

const showNewFolderDialog = ref(false)
const showDeleteFolderDialog = ref(false)

const folders = ref([])
const files = ref([])

const currentFolder = ref(null)
const breadcrumbs = ref([])

const selectedFolder = ref(null)

const refreshFolders = async () => {
    await loadFolders(currentFolder.value?.id)
}

const loadFolders = async (parentId = null) => {
    let url = '/api/drive/folders'

    if (parentId) {
        url += `?parent_id=${parentId}`
    }

    const response = await fetch(url, {
        credentials: 'include',
        headers: {
            Accept: 'application/json',
        },
    })

    const data = await response.json()

    folders.value = data.folders ?? data
    files.value = data.files ?? []
}

const openFolder = async (folder) => {
    currentFolder.value = folder

    breadcrumbs.value.push({
        id: folder.id,
        name: folder.name,
    })

    await loadFolders(folder.id)
}

const goHome = async () => {
    currentFolder.value = null
    breadcrumbs.value = []

    await loadFolders()
}

const navigateBreadcrumb = async (index) => {
    const folder = breadcrumbs.value[index]

    breadcrumbs.value = breadcrumbs.value.slice(0, index + 1)

    currentFolder.value = folder

    await loadFolders(folder.id)
}

const confirmDeleteFolder = (folder) => {
    selectedFolder.value = folder
    showDeleteFolderDialog.value = true
}

onMounted(async () => {
    await loadFolders()
})
</script>

<template>
<div class="max-w-5xl mx-auto px-4 py-8 space-y-6">
    <header class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h1 class="font-display text-3xl">Drive</h1>
            <p class="text-sm text-muted-foreground">Shared files for the LAMP community.</p>
        </div>
        <div class="flex gap-2">
            <Button type="plain" @click="showNewFolderDialog = true">
                <FolderPlusIcon />
                New folder
            </Button>

            <Button type="primary">
                <UploadIcon />
                Upload
            </Button>
            <input multiple="" class="hidden" type="file">
        </div>
    </header>
    <div class="flex items-center gap-1 text-sm flex-wrap">
        <button
            @click="goHome"
            class="px-2 py-1 rounded hover:bg-accent font-medium"
        >
            <HomeIcon />
            Drive
        </button>

        <template
            v-for="(crumb, index) in breadcrumbs"
            :key="crumb.id"
        >
            <CaretIcon />

            <button
                @click="navigateBreadcrumb(index)"
                class="px-2 py-1 rounded hover:bg-accent"
            >
                {{ crumb.name }}
            </button>
        </template>

    </div>
    <div class="rounded-xl border bg-card text-card-foreground shadow divide-y">
        <div v-if="folders.length == 0" class="text-center py-16 text-sm text-muted-foreground">This folder is empty.</div>
        <div
            v-else
            v-for="folder in folders"
            :key="folder.id"
            class="flex items-center gap-3 px-4 py-3 hover:bg-accent/40 transition"
        >
            <button @click="openFolder(folder)" class="flex items-center gap-3 flex-1 min-w-0 text-left">
                <FolderOrangeIcon />

                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">
                        {{ folder.name }}
                    </div>

                    <div class="text-xs text-muted-foreground">
                        Folder · {{ folder.created_human }}
                    </div>
                </div>
            </button>

            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs text-muted-foreground">
                    {{ folder.owner?.name }}
                </span>
            </div>

            <Button type="icon" @click.stop="confirmDeleteFolder(folder)">
                <TrashIcon />
            </Button>
        </div>
    </div>

    <CreateFolderDialog
        :open="showNewFolderDialog"
        :parent-id="currentFolder?.id"
        @close="showNewFolderDialog = false"
        @created="refreshFolders"
    />

    <DeleteFolderDialog
        :open="showDeleteFolderDialog"
        :folder="selectedFolder"
        @close="showDeleteFolderDialog = false"
        @deleted="refreshFolders"
    />
</div>
</template>
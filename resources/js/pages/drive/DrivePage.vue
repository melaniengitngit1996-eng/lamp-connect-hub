<script setup>
import { useAuth } from '../../stores/auth'

import { ref, onMounted, watch, computed } from 'vue'

const { can } = useAuth();

import Dialog from '../../components/Dialog.vue'
import Button from '../../components/Button.vue'
import FolderPlusIcon from '../../icons/FolderPlusIcon.vue'
import UploadIcon from '../../icons/UploadIcon.vue'
import HomeIcon from '../../icons/HomeIcon.vue'
import FolderOrangeIcon from '../../icons/FolderOrangeIcon.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import RedTrashIcon from '../../icons/RedTrashIcon.vue'
import CaretIcon from '../../icons/CaretIcon.vue'
import FileBlueIcon from '../../icons/FileBlueIcon.vue'
import SearchIcon from '../../icons/SearchIcon.vue'
import FolderShrink from '../../icons/FolderShrink.vue'
import ShareIcon from '.././../icons/ShareIcon.vue'
import PulseIcon from '../../icons/PulseIcon.vue';
import PencilIconGray from '../../icons/PencilIconGray.vue';
import FolderArrowIcon from '../../icons/FolderArrowIcon.vue';
import StarredIcon from '../../icons/StarredIcon.vue';
import UnstarredIcon from '../../icons/UnstarredIcon.vue';
import EllipsisVerticalIcon from '../../icons/EllipsisVerticalIcon.vue';

import CreateFolderDialog from '../../pages/drive/CreateFolderDialog.vue'
import DeleteFolderDialog from '../../pages/drive/DeleteFolderDialog.vue'
import UploadFileDialog from '../../pages/drive/UploadFileDialog.vue'
import PreviewDialog from '../../pages/drive/PreviewDialog.vue'
import DeleteFileDialog from '../../pages/drive/DeleteFileDialog.vue'
import ShareDialog from '../../pages/drive/ShareDialog.vue'
import ActivityDialog from './ActivityDialog.vue';
import RenameDialog from './RenameDialog.vue';
import MoveDialog from './MoveDialog.vue';

import Popover from '@/components/Popover.vue'

const showNewFolderDialog = ref(false)
const showDeleteFolderDialog = ref(false)
const showUploadDialog = ref(false)
const showPreviewDialog = ref(false)
const showDeleteFileDialog = ref(false)
const showShareDialog = ref(false)
const showPulseDialog = ref(false)
const showRenameDialog = ref(false)
const showMoveDialog = ref(false)

const folders = ref([])
const files = ref([])

const currentFolder = ref(null)
const breadcrumbs = ref([])

const selectedFolder = ref(null)
const selectedFile = ref(null)
const selectedItem = ref(null)
const itemType = ref(null)
const loadingFolder = ref(false)
const view = ref('drive')

const search = ref('')

let searchTimeout

watch(search, () => {
    clearTimeout(searchTimeout)

    searchTimeout = setTimeout(() => {
        loadFolders(currentFolder.value?.id)
    }, 300)
})

const refreshFolders = async () => {
    await loadFolders(currentFolder.value?.id)
}

const loadFolders = async (parentId = null) => {
    const params = new URLSearchParams()

    if (parentId !== null) {
        params.append('parent_id', parentId)
    }

    if (search.value) {
        params.append('search', search.value)
    }

    if (view.value === 'starred') {
        params.append('starred', 1)
    }

    const response = await fetch(
        `/api/drive/folders?${params.toString()}`,
        {
            credentials: 'include',
            headers: {
                Accept: 'application/json',
            },
        }
    )

    const data = await response.json()

    folders.value = data.folders ?? []
    files.value = data.files ?? []
}

const openFolder = async (folder) => {
    if (loadingFolder.value) {
        return
    }

    if (currentFolder.value?.id === folder.id) {
        return
    }

    loadingFolder.value = true

    try {
        currentFolder.value = folder

        breadcrumbs.value.push({
            id: folder.id,
            name: folder.name,
        })

        await loadFolders(folder.id)
    } finally {
        loadingFolder.value = false
    }
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

const confirmDeleteFile = (file) => {
    selectedFile.value = file
    showDeleteFileDialog.value = true
}

const previewFile = (file) => {
    selectedFile.value = file
    showPreviewDialog.value = true
}

const pulseFile = (file) => {
    selectedFile.value = file
    showPulseDialog.value = true
}

const openShareDialog = (item, type) => {
    selectedItem.value = item
    itemType.value = type
    showShareDialog.value = true
}

const openRenameDialog = (item, type) => {
    selectedItem.value = item
    itemType.value = type
    showRenameDialog.value = true
}

const openMoveDialog = (item, type) => {
    selectedItem.value = item
    itemType.value = type
    showMoveDialog.value = true
}

const openFileLocation = async (file) => {
    search.value = ''

    breadcrumbs.value = file.path_folders

    currentFolder.value =
        file.path_folders[file.path_folders.length - 1] ?? null

    await loadFolders(currentFolder.value?.id)
}

const openFolderLocation = async (folder) => {
    search.value = ''

    breadcrumbs.value = folder.path_folders

    currentFolder.value =
        folder.path_folders[folder.path_folders.length - 1]

    await loadFolders(folder.id)
}

const toggleFavorite = async (item, type) => {
    const endpoint =
        type === 'folder'
            ? `/api/drive/folders/${item.id}/favorite`
            : `/api/drive/files/${item.id}/favorite`

    const response = await fetch(endpoint, {
        method: 'POST',
        credentials: 'include',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': document.querySelector(
                'meta[name="csrf-token"]'
            )?.content,
        },
    })

    if (!response.ok) {
        return
    }

    const data = await response.json()

    item.is_favorited = data.favorited
}

onMounted(async () => {
    await loadFolders()
})

const totalMatches = computed(() => {
    return folders.value.length + files.value.length
})

const matchLabel = computed(() => {
    return totalMatches.value === 1
        ? 'match'
        : 'matches'
})

const toggleStarred = async () => {
    if (view.value === 'starred') {
        view.value = 'drive'
    } else {
        view.value = 'starred'
    }

    await loadFolders(currentFolder.value?.id)
}

const handleFolderClick = async (folder) => {
    if (search.value) {
        return openFolderLocation(folder)
    }

    return openFolder(folder)
}

const handleShare = (item, type, close) => {
    close()
    openShareDialog(item, type)
}

const handleRename = (item, type, close) => {
    close()
    openRenameDialog(item, type)
}

const handleMove = (item, type, close) => {
    close()
    openMoveDialog(item, type)
}

const handleDeleteFolder = (folder, close) => {
    close()
    confirmDeleteFolder(folder)
}

const handleDeleteFile = (file, close) => {
    close()
    confirmDeleteFile(file)
}

const handleActivity = (file, close) => {
    close()
    pulseFile(file)
}
</script>

<template>
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">
        <header class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="font-display text-3xl">Drive</h1>
                <p class="text-sm text-muted-foreground">Shared files for the LAMP Church community.</p>
            </div>
            <div class="flex gap-2">
                <Button :type="view === 'starred' ? 'primary' : 'plain'" @click="toggleStarred">
                    <UnstarredIcon />
                    Starred
                </Button>

                <Button v-if="can('drive.upload')" type="plain" @click="showNewFolderDialog = true">
                    <FolderPlusIcon />
                    New folder
                </Button>

                <Button v-if="can('drive.upload')" type="primary" @click="showUploadDialog = true">
                    <UploadIcon />
                    Upload
                </Button>
                <input multiple="" class="hidden" type="file">
            </div>
        </header>
        <div class="relative">
            <SearchIcon />

            <input v-model="search"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-9 pr-9"
                placeholder="Search all files and folders…">
        </div>
        <div v-if="search" class="text-sm text-muted-foreground">
            Search results for
            <span class="font-medium text-foreground">
                "{{ search }}"
            </span>
            · {{ totalMatches }} {{ matchLabel }}
        </div>
        <div v-if="!search" class="flex items-center gap-1 text-sm flex-wrap">
            <button @click="goHome" class="px-2 py-1 rounded hover:bg-accent font-medium">
                <HomeIcon />
                Drive
            </button>

            <template v-for="(crumb, index) in breadcrumbs" :key="crumb.id">
                <CaretIcon />

                <button @click="navigateBreadcrumb(index)" class="px-2 py-1 rounded hover:bg-accent">
                    {{ crumb.name }}
                </button>
            </template>

        </div>
        <div class="rounded-xl border bg-card text-card-foreground shadow divide-y">
            <div v-if="folders.length === 0 && files.length === 0"
                class="text-center py-16 text-sm text-muted-foreground">
                This folder is empty.
            </div>
            <div v-for="folder in folders" :key="folder.id"
                class="flex items-center gap-3 px-4 py-3 hover:bg-accent/40 transition">
                <button @click="handleFolderClick(folder)" class="flex items-center gap-3 flex-1 min-w-0 text-left">
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

                <button v-if="search" @click.stop="openFolderLocation(folder)"
                    class="hidden md:flex items-center gap-1 text-xs text-muted-foreground hover:text-foreground px-2 py-1 rounded hover:bg-accent max-w-[40%] truncate"
                    title="Open location">
                    <FolderShrink />
                    <span class="truncate">{{ folder.path_human }}</span>
                </button>

                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-xs text-muted-foreground">
                        {{ folder.owner?.name }}
                    </span>
                </div>

                <Button type="icon" @click.stop="toggleFavorite(folder, 'folder')">
                    <StarredIcon v-if="folder.is_favorited" />
                    <UnstarredIcon v-else />
                </Button>

                <Popover>
                    <template #trigger>
                        <Button type="icon">
                            <EllipsisVerticalIcon />
                        </Button>
                    </template>

                    <template #content="{ close }">

                        <button v-if="folder.can_manage && can('drive.share')"
                            @click.stop="handleShare(folder, 'folder', close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <ShareIcon />
                            Share
                        </button>

                        <button v-if="folder.can_manage && can('drive.update')"
                            @click.stop="handleRename(folder, 'folder', close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <PencilIconGray />
                            Rename
                        </button>

                        <button @click.stop="handleMove(folder, 'folder', close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <FolderArrowIcon />
                            Move
                        </button>

                        <div class="my-1 h-px bg-border" />

                        <button v-if="folder.can_manage && can('drive.delete')"
                            @click.stop="handleDeleteFolder(folder, close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm text-destructive hover:bg-accent">
                            <RedTrashIcon />
                            Delete
                        </button>

                    </template>
                </Popover>
            </div>

            <div v-for="file in files" :key="file.id"
                class="flex items-center gap-3 px-4 py-3 hover:bg-accent/40 transition">
                <button class="flex items-center gap-3 flex-1 min-w-0 text-left">
                    <FileBlueIcon />

                    <div class="flex-1 min-w-0" @click="previewFile(file)">
                        <div class="text-sm font-medium truncate">
                            {{ file.name }}
                        </div>

                        <div class="text-xs text-muted-foreground">
                            {{ file.size_human }} · {{ file.created_human }}
                        </div>
                    </div>
                </button>

                <button v-if="search" @click.stop="openFileLocation(file)"
                    class="hidden md:flex items-center gap-1 text-xs text-muted-foreground hover:text-foreground px-2 py-1 rounded hover:bg-accent max-w-[40%] truncate"
                    title="Open location">
                    <FolderShrink />
                    <span class="truncate">{{ file.path_human }}</span>
                </button>

                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-xs text-muted-foreground">
                        {{ file.uploader?.name }}
                    </span>
                </div>

                <Button type="icon" @click.stop="toggleFavorite(file, 'file')">
                    <StarredIcon v-if="file.is_favorited" />
                    <UnstarredIcon v-else />
                </Button>

                <Popover>
                    <template #trigger>
                        <Button type="icon">
                            <EllipsisVerticalIcon />
                        </Button>
                    </template>

                    <template #content="{ close }">

                        <button v-if="file.can_manage && can('drive.share')"
                            @click.stop="handleShare(file, 'file', close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <ShareIcon />
                            Share
                        </button>

                        <button v-if="file.can_manage && can('drive.update')"
                            @click.stop="handleRename(file, 'file', close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <PencilIconGray />
                            Rename
                        </button>

                        <button @click.stop="handleActivity(file, close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <PulseIcon />
                            Activity
                        </button>

                        <button @click.stop="handleMove(file, 'file', close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent">
                            <FolderArrowIcon />
                            Move
                        </button>

                        <div class="my-1 h-px bg-border" />

                        <button v-if="file.can_manage && can('drive.delete')"
                            @click.stop="handleDeleteFile(file, close)"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm text-destructive hover:bg-accent">
                            <RedTrashIcon />
                            Delete
                        </button>

                    </template>
                </Popover>
            </div>
        </div>

        <CreateFolderDialog :open="showNewFolderDialog" :parent-id="currentFolder?.id"
            @close="showNewFolderDialog = false" @created="refreshFolders" />

        <DeleteFolderDialog :open="showDeleteFolderDialog" :folder="selectedFolder"
            @close="showDeleteFolderDialog = false" @deleted="refreshFolders" />

        <DeleteFileDialog :open="showDeleteFileDialog" :file="selectedFile" @close="showDeleteFileDialog = false"
            @deleted="refreshFolders" />

        <UploadFileDialog :open="showUploadDialog" :folder-id="currentFolder?.id" @close="showUploadDialog = false"
            @uploaded="refreshFolders" />

        <PreviewDialog :open="showPreviewDialog" :file="selectedFile" @close="showPreviewDialog = false" />

        <ShareDialog :open="showShareDialog" :item="selectedItem" :type="itemType" @close="showShareDialog = false" />

        <ActivityDialog :open="showPulseDialog" :file="selectedFile" @close="showPulseDialog = false" />

        <RenameDialog :open="showRenameDialog" :item="selectedItem" :type="itemType" @close="showRenameDialog = false"
            @renamed="refreshFolders" />

        <MoveDialog :open="showMoveDialog" :item="selectedItem" :type="itemType" @close="showMoveDialog = false"
            @moved="refreshFolders" />
    </div>
</template>
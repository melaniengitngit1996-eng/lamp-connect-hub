<script setup>
import { ref, reactive, onMounted } from 'vue'

const activeTab = ref('general')

const settings = reactive({
    general: {},
    chat: {},
    feed: {},
    drive: {},
})

const loading = ref(false)
const saving = ref({})

import BuildingsIcon from '../../icons/BuildingsIcon.vue';
import ChatIcon from '../../icons/ChatIcon.vue';
import TilesIcon from '../../icons/TilesIcon.vue';
import FolderIcon from '../../icons/FolderIcon.vue';
import DiskIcon from '../../icons/DiskIcon.vue';

import Switch from '@/components/Switch.vue'
import Button from '@/components/Button.vue'

async function loadSettings() {
    loading.value = true

    try {
        const { data } = await axios.get('/api/settings')

        Object.assign(settings, data)
    } finally {
        loading.value = false
    }
}

async function saveSetting(key, value) {
    saving.value[key] = true

    try {
        await axios.patch('/api/settings', {
            key,
            value,
        })
    } finally {
        saving.value[key] = false
    }
}

function updateSetting(path, value) {
    const [group, key] = path.split('.')

    settings[group][key] = value
}

function bindSetting(path) {
    return async (value) => {
        updateSetting(path, value)

        await saveSetting(path, value)
    }
}

onMounted(loadSettings)
</script>
<template>
    <main class="flex-1 min-w-0 pb-16 md:pb-0">
        <div class="max-w-5xl p-6 md:p-8">
            <header class="mb-6 flex items-center gap-3">
                <div class="min-w-0 flex-1">
                    <h1 class="font-display text-2xl leading-tight">System Settings</h1>
                    <p class="text-sm text-muted-foreground">
                        Portal-wide options for the LAMP community.
                    </p>
                </div>
            </header>

            <div class="grid gap-6 md:grid-cols-[220px_1fr]">
                <!-- Sidebar -->
                <nav class="flex gap-1 overflow-x-auto pb-1 md:flex-col md:overflow-visible">
                    <button @click="activeTab = 'general'" type="button" :class="[
                        'flex items-center gap-2 rounded-lg px-3 py-2 text-left text-sm whitespace-nowrap transition',
                        activeTab === 'general'
                            ? 'bg-accent text-accent-foreground font-medium'
                            : 'text-muted-foreground hover:bg-accent/60'
                    ]">
                        <BuildingsIcon />
                        <span class="flex-1">General</span>
                    </button>

                    <button @click="activeTab = 'chat'" type="button" :class="[
                        'flex items-center gap-2 rounded-lg px-3 py-2 text-left text-sm whitespace-nowrap transition',
                        activeTab === 'chat'
                            ? 'bg-accent text-accent-foreground font-medium'
                            : 'text-muted-foreground hover:bg-accent/60'
                    ]">
                        <ChatIcon />
                        <span class="flex-1">Chat</span>
                    </button>

                    <button @click="activeTab = 'feed'" type="button" :class="[
                        'flex items-center gap-2 rounded-lg px-3 py-2 text-left text-sm whitespace-nowrap transition',
                        activeTab === 'feed'
                            ? 'bg-accent text-accent-foreground font-medium'
                            : 'text-muted-foreground hover:bg-accent/60'
                    ]">
                        <TilesIcon />
                        <span class="flex-1">Feed</span>
                    </button>

                    <button @click="activeTab = 'drive'" type="button" :class="[
                        'flex items-center gap-2 rounded-lg px-3 py-2 text-left text-sm whitespace-nowrap transition',
                        activeTab === 'drive'
                            ? 'bg-accent text-accent-foreground font-medium'
                            : 'text-muted-foreground hover:bg-accent/60'
                    ]">
                        <FolderIcon />
                        <span class="flex-1">Drive</span>
                    </button>
                </nav>

                <!-- Content -->
                <div v-if="activeTab === 'general'" class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="space-y-1.5 p-6">
                        <h2 class="text-base font-semibold tracking-tight">General</h2>
                        <p class="text-sm text-muted-foreground">
                            Identity and defaults for the whole portal.
                        </p>
                    </div>

                    <div class="space-y-6 p-6 pt-0">
                        <div class="divide-y">
                            <!-- Organization Name -->
                            <div
                                class="flex flex-col gap-3 py-4 first:pt-0 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium">
                                        Organization name
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        Shown in headings and page titles across the portal.
                                    </p>
                                </div>

                                <div class="shrink-0 sm:w-64">
                                    <input id="org_name" v-model="settings.general.organization_name" @blur="saveSetting(
                                        'general.organization_name',
                                        settings.general.organization_name
                                    )" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                                        placeholder="LAMP Church" />
                                </div>
                            </div>

                            <!-- Support Email -->
                            <div
                                class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label for="support_email" class="text-sm font-medium">
                                        Support email
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        Where members are told to reach out for help or account
                                        issues.
                                    </p>
                                </div>

                                <div class="shrink-0 sm:w-64">
                                    <input id="support_email" v-model="settings.general.support_email" @blur="saveSetting(
                                        'general.support_email',
                                        settings.general.support_email
                                    )" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                                        placeholder="support@example.org" />
                                </div>
                            </div>

                            <!-- Auto Approve -->
                            <div
                                class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label for="auto_approve_members" class="text-sm font-medium">
                                        Auto-approve new sign-ups
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        When off, an administrator must approve each new member before
                                        they get access.
                                    </p>
                                </div>

                                <Switch v-model="settings.general.auto_approve_members"
                                    @change="saveSetting('general.auto_approve_members', $event)" />
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="activeTab === 'chat'" class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="flex flex-col space-y-1.5 p-6"
                        data-tsd-source="/src/routes/_app.admin.settings.tsx:153:11">
                        <div class="font-semibold tracking-tight text-base"
                            data-tsd-source="/src/routes/_app.admin.settings.tsx:154:13">
                            Chat
                        </div>
                        <div class="text-sm text-muted-foreground"
                            data-tsd-source="/src/routes/_app.admin.settings.tsx:155:13">
                            Control which conversation types members can use.
                        </div>
                    </div>
                    <div class="p-6 pt-0 space-y-6">
                        <div class="divide-y">
                            <div
                                class="flex flex-col gap-3 py-4 first:pt-0 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium" for="personal_chat_enabled">
                                        Personal chats
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        Allow members to start and use one-on-one direct messages in the Chat module.
                                    </p>
                                </div>

                                <div class="shrink-0 sm:w-64 sm:text-right">
                                    <Switch id="personal_chat_enabled" v-model="settings.chat.personal_chat_enabled"
                                        @change="saveSetting('chat.personal_chat_enabled', $event)" />
                                </div>
                            </div>

                            <div
                                class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium" for="group_chat_enabled">
                                        Group chats
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        Allow members to create and join group conversations.
                                    </p>
                                </div>

                                <div class="shrink-0 sm:w-64 sm:text-right">
                                    <Switch id="group_chat_enabled" v-model="settings.chat.group_chat_enabled"
                                        @change="saveSetting('chat.group_chat_enabled', $event)" />
                                </div>
                            </div>
                            <div
                                class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label
                                        class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-medium"
                                        for="drive_max_upload_mb">Maximum upload size (MB)</label>
                                    <p class="text-xs text-muted-foreground max-w-md">Largest single file a member is
                                        allowed to upload.</p>
                                </div>
                                <div class="sm:w-64 shrink-0 sm:text-right">
                                    <input v-model="settings.chat.max_upload_size" @blur="saveSetting(
                                        'chat.max_upload_size',
                                        settings.chat.max_upload_size
                                    )" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        id="chat_max_upload_mb" min="1" max="1024" type="number" value="50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="activeTab === 'feed'" class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <div class="font-semibold tracking-tight text-base">Feed</div>
                        <div class="text-sm text-muted-foreground">Posting and interaction rules for the community feed.
                        </div>
                    </div>
                    <div class="p-6 pt-0 space-y-6">
                        <div class="divide-y">
                            <div
                                class="flex flex-col gap-3 py-4 first:pt-0 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium" for="feed_posting_enabled">
                                        Member posting
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        When off, only administrators can publish new posts to the feed.
                                    </p>
                                </div>

                                <div class="shrink-0 sm:w-64 sm:text-right">
                                    <Switch id="feed_posting_enabled" v-model="settings.feed.feed_posting_enabled"
                                        @change="saveSetting('feed.feed_posting_enabled', $event)" />
                                </div>
                            </div>

                            <div
                                class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium" for="feed_comments_enabled">
                                        Comments
                                    </label>

                                    <p class="max-w-md text-xs text-muted-foreground">
                                        Allow members to comment on feed posts.
                                    </p>
                                </div>

                                <div class="shrink-0 sm:w-64 sm:text-right">
                                    <Switch id="feed_comments_enabled" v-model="settings.feed.feed_comments_enabled"
                                        @change="saveSetting('feed.feed_comments_enabled', $event)" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="activeTab === 'drive'" class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <div class="font-semibold tracking-tight text-base">Drive</div>
                        <div class="text-sm text-muted-foreground">File storage limits and sharing behaviour.</div>
                    </div>
                    <div class="p-6 pt-0 space-y-6">
                        <div class="divide-y">
                            <div
                                class="flex flex-col gap-3 py-4 first:pt-0 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                <div class="space-y-1"><label
                                        class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-medium"
                                        for="drive_max_upload_mb">Maximum upload size (MB)</label>
                                    <p class="text-xs text-muted-foreground max-w-md">Largest single file a member is
                                        allowed to upload.</p>
                                </div>
                                <div class="sm:w-64 shrink-0 sm:text-right">
                                    <input v-model="settings.drive.max_upload_size" @blur="saveSetting(
                                        'drive.max_upload_size',
                                        settings.drive.max_upload_size
                                    )" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        id="drive_max_upload_mb" min="1" max="1024" type="number" value="50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
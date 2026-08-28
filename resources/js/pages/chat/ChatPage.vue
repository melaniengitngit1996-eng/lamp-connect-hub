<script setup>
import { ref, onMounted, nextTick, computed } from 'vue'
import { useAuth } from '../../stores/auth'
import { useSettings } from '../../stores/settings'
const { logout, user, fetchUser, can } = useAuth()
const settings = useSettings()


import GroupChatDialog from '../../pages/chat/GroupChatDialog.vue'
import DirectChatDialog from '../../pages/chat/DirectChatDialog.vue'
import ChatMembersDialog from '../../pages/chat/ChatMembersDialog.vue'
import GroupChatRenameDialog from '../../pages/chat/GroupChatRenameDialog.vue'

import ChannelIcon from '../../icons/ChannelIcon.vue'
import PlusIcon from '../../icons/PlusIcon.vue'
import PaperClipIcon from '../../icons/PaperClipIcon.vue'
import PlaneIcon from '../../icons/PlaneIcon.vue'
import GroupIcon from '../../icons/GroupIcon.vue'
import PencilIconGray from '../../icons/PencilIconGray.vue'

const showNewGroupChatDialog = ref(false)
const showNewDirectChatDialog = ref(false)
const showChatMembersDialog = ref(false)
const showGroupRenameDialog = ref(false)

const openNewGroupChatDialog = (item, type) => {
	showNewGroupChatDialog.value = true
}

const openNewDirectChatDialog = (item, type) => {
	showNewDirectChatDialog.value = true
}

const openChatMembersDialog = (item, type) => {
	showChatMembersDialog.value = true
}

const openGroupRenameDialog = () => {
	showGroupRenameDialog.value = true
}

const channels = ref([])
const groups = ref([])
const selectedConversation = ref(null)
const messages = ref([])
const members = ref([])
const directMessages = ref([])
const newMessage = ref('')
const messagesContainer = ref(null)
const isSending = ref(false)
const sendError = ref('')
const selectedFile = ref(null)
const fileInput = ref(null)

const isSelectingFile = ref(false)

const openFilePicker = () => {
	isSelectingFile.value = true
	fileInput.value?.click()
}

const handleFileSelected = (event) => {
	const file = event.target.files?.[0]

	if (!file) {
		isSelectingFile.value = false
		return
	}

	selectedFile.value = file
	isSelectingFile.value = false
}

const handleFileCancelled = () => {
	isSelectingFile.value = false
}

const loadChats = async () => {
	const { data } = await axios.get('/api/chat')

	channels.value = data.channels
	groups.value = data.groups
	directMessages.value = data.direct_messages

	// Desktop only
	if (window.innerWidth >= 768) {
		const firstConversation =
			// channels.value[0] ??
			groups.value[0] ??
			directMessages.value[0]

		if (firstConversation) {
			await loadConversation(firstConversation)
		}
	}
}

const loadConversation = async (conversation) => {
	try {
		const { data } = await axios.get(
			`/api/chat/conversations/${conversation.id}`
		)

		selectedConversation.value = data.conversation
		members.value = data.members
		messages.value = data.messages.data ?? data.messages

		await axios.post(
			`/api/chat/conversations/${conversation.id}/read`
		)

		// Remove the badge immediately
		updateConversationUnreadCount(conversation.id)

		await scrollToBottom()

	} catch (error) {
		console.error(error)
	}
}

const updateConversationUnreadCount = (conversationId) => {
	const conversation = [
		...channels.value,
		...groups.value,
		...directMessages.value,
	].find(item => item.id === conversationId)

	if (conversation) {
		conversation.unread_count = 0
	}
}

const sendMessage = async () => {
	if (
		(!newMessage.value.trim() && !selectedFile.value) ||
		isSending.value
	) {
		return
	}

	isSending.value = true
	sendError.value = ''

	try {
		const formData = new FormData()

		if (newMessage.value.trim()) {
			formData.append('message', newMessage.value)
		}

		if (selectedFile.value) {
			formData.append('file', selectedFile.value)
		}

		const { data } = await axios.post(
			`/api/chat/conversations/${selectedConversation.value.id}/messages`,
			formData
		)

		messages.value.push(data.data)

		newMessage.value = ''
		selectedFile.value = null

		if (fileInput.value) {
			fileInput.value.value = ''
		}

		await scrollToBottom()
	} catch (error) {
		console.error(error)

		sendError.value =
			error.response?.data?.message ||
			'Unable to send message. Please try again.'
	} finally {
		isSending.value = false
	}
}

const scrollToBottom = async () => {
	await nextTick()

	if (messagesContainer.value) {
		messagesContainer.value.scrollTop =
			messagesContainer.value.scrollHeight
	}
}

const handleConversationCreated = async (conversation) => {
	showNewDirectChatDialog.value = false

	await loadChats()
	await loadConversation(conversation)
}

const handleGroupCreated = async (conversation) => {
	showNewGroupChatDialog.value = false

	await loadChats()
	await loadConversation(conversation)
}

const isMobile = computed(() => window.innerWidth < 768)

const handleGroupRenamed = async (conversation) => {
	showGroupRenameDialog.value = false

	await loadChats()
	await loadConversation(conversation)
}

const formatFileSize = (bytes) => {
	if (!bytes) {
		return ''
	}

	if (bytes < 1024) {
		return `${bytes} B`
	}

	if (bytes < 1024 * 1024) {
		return `${(bytes / 1024).toFixed(1)} KB`
	}

	if (bytes < 1024 * 1024 * 1024) {
		return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
	}

	return `${(bytes / (1024 * 1024 * 1024)).toFixed(1)} GB`
}

onMounted(() => {
	loadChats()
	settings.load()
})
</script>

<style scoped>
.conversation-height {
	height: calc(100vh - 140px);
}

@media (max-width: 767px) {
	.conversation-height {
		height: calc(100vh - 250px);
	}

	.conversation-with-attachment {
		height: calc(100vh - 295px);
	}

	.conversation-with-error {
		height: calc(100vh - 295px);
	}

	.conversation-with-attachment-error {
		height: calc(100vh - 340px);
	}
}

.chat-file {
	width: 280px;
	max-width: 100%;
}

.chat-file-info {
	flex: 1;
	min-width: 0;
	overflow: hidden;
}

.chat-file-name {
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

@media (max-width: 767px) {
	.chat-file {
		width: 220px;
	}
}
</style>

<template>
	<div class="h-full min-h-0 overflow-hidden flex">

		<aside :class="[
			'border-r bg-card/30',
			selectedConversation
				? 'hidden md:block md:w-72 md:flex-col'
				: 'flex w-full flex-col md:w-72'
		]">
			<div class="px-4 py-4 border-b">
				<h1 class="font-display text-xl">Messages</h1>
				<p class="text-xs text-muted-foreground">Group chats</p>
			</div>
			<div class="flex-1 overflow-y-auto px-2 py-3 space-y-5">
				<!-- <div>
				<div class="flex items-center justify-between px-2 mb-1">
					<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Channels</span>
				</div>
				<ul v-if="channels.length" class="space-y-0.5">
					<li v-for="channel in channels"
						:key="channel.id">
						<button
							@click="loadConversation(channel)"
							:class="[
								'w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition',
								selectedConversation?.id === channel.id && 'bg-accent'
							]"
						>
							<div class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
								<ChannelIcon />
							</div>
							<span class="text-sm truncate flex-1">{{ channel.name }}</span>
						</button>
					</li>
				</ul>
				<div v-else class="italic px-2 py-3 text-center text-muted-foreground text-xs">
					No channels available
				</div>
			</div> -->
				<div v-if="settings.chat.group_chat_enabled">
					<div class="flex items-center justify-between px-2 mb-1">
						<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Group
							chats</span>
						<button v-if="can('chat.manager')" @click="openNewGroupChatDialog"
							class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-6 w-6">
							<PlusIcon />
						</button>
					</div>
					<ul v-if="groups.length" class="space-y-0.5">
						<li v-for="group in groups" :key="group.id">
							<button @click="loadConversation(group)" :class="[
								'w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition',
								selectedConversation?.id === group.id && 'bg-accent'
							]">
								<div
									class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
									<GroupIcon />
								</div>
								<span class="text-sm truncate flex-1">
									{{ group.name }}
								</span>

								<span v-if="group.unread_count > 0"
									class="flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] font-medium text-primary-foreground">
									{{ group.unread_count > 99 ? '99+' : group.unread_count }}
								</span>
							</button>
						</li>
					</ul>
					<div v-else class="italic px-2 py-3 text-center text-muted-foreground text-xs">
						No group chats yet
					</div>
				</div>
				<div v-if="settings.chat.personal_chat_enabled">
					<div class="flex items-center justify-between px-2 mb-1">
						<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Direct
							messages</span>
						<button @click="openNewDirectChatDialog"
							class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-6 w-6">
							<PlusIcon />
						</button>
					</div>
					<ul v-if="directMessages.length" class="space-y-0.5">
						<li v-for="directMessage in directMessages" :key="directMessage.id">
							<button @click="loadConversation(directMessage)" :class="[
								'w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition',
								selectedConversation?.id === directMessage.id && 'bg-accent'
							]">
								<span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9">
									<span
										class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">
										{{ directMessage.initials }}
									</span>
								</span>
								<span class="text-sm truncate flex-1">
									{{ directMessage.name }}
								</span>

								<span v-if="directMessage.unread_count > 0"
									class="flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] font-medium text-primary-foreground">
									{{ directMessage.unread_count > 99 ? '99+' : directMessage.unread_count }}
								</span>
							</button>
						</li>
					</ul>
					<div v-else class="italic px-2 py-3 text-center text-muted-foreground text-xs">
						No conversations yet.
					</div>
				</div>
			</div>
		</aside>

		<div :class="[
			'flex-1 min-w-0 flex-col',
			selectedConversation
				? 'flex'
				: 'hidden md:flex'
		]">
			<header class="px-6 py-4 border-b flex items-center gap-3" v-if="selectedConversation">
				<button @click="selectedConversation = null"
					class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-9 w-9 md:hidden shrink-0"><svg
						xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="lucide lucide-chevron-left h-5 w-5" aria-hidden="true">
						<path d="m15 18-6-6 6-6"></path>
					</svg></button>
				<div v-if="selectedConversation?.type != 'direct'"
					class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
					<GroupIcon />
				</div>
				<span v-else class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9">
					<span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">
						{{ selectedConversation?.initials }}
					</span>
				</span>
				<div class="min-w-0">
					<div class="flex items-center gap-1.5">
						<div class="font-display text-lg truncate">
							{{ selectedConversation?.name }}
						</div>

						<button v-if="
							selectedConversation?.type === 'group' &&
							selectedConversation?.is_owner
						" type="button" @click="openGroupRenameDialog"
							class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
							title="Rename group">
							<PencilIconGray class="h-3.5 w-3.5" />
						</button>
					</div>

					<div v-if="selectedConversation?.type !== 'direct'" class="text-xs text-muted-foreground">
						{{ selectedConversation?.members_count }} members
					</div>
					<div v-else-if="selectedConversation?.type === 'direct'" class="text-xs text-muted-foreground">
						Direct Message
					</div>
				</div>
				<button v-if="
					selectedConversation?.type === 'group' &&
					selectedConversation?.is_owner
				" @click="openChatMembersDialog"
					class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs ml-auto">
					<GroupIcon /> Members
				</button>
			</header>
			<div v-if="selectedConversation" :class="[
				'overflow-hidden conversation-height',
				selectedFile && 'conversation-with-attachment',
				sendError && 'conversation-with-error',
				selectedFile && sendError && 'conversation-with-attachment-error'
			]">
				<div ref="messagesContainer" class="flex-1 h-full overflow-y-auto px-4 py-6 space-y-4">
					<div v-if="!messages.length" class="h-full flex items-center justify-center text-muted-foreground">
						No messages yet.
						Start the conversation.
					</div>
					<div v-else v-for="(message, index) in messages" :key="message.id">
						<!-- Date separator -->
						<div v-if="
							index === 0 ||
							messages[index - 1]?.date_key !== message.date_key
						" class="flex items-center gap-3 my-5">
							<div class="flex-1"></div>

							<span class="text-[11px] font-medium text-muted-foreground">
								{{ message.date_label }}
							</span>

							<div class="flex-1"></div>
						</div>

						<!-- Message -->
						<div :class="[
							'flex gap-2 mb-4',
							message.sender.id === user.id
								? 'flex-row-reverse'
								: ''
						]">
							<span class="relative flex overflow-hidden rounded-full h-8 w-8 shrink-0">
								<span
									class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">
									{{ message.sender.initials }}
								</span>
							</span>

							<div :class="[
								'max-w-[75%] flex flex-col gap-1',
								message.sender.id === user.id
									? 'items-end'
									: 'items-start'
							]">
								<div class="text-xs text-muted-foreground">
									{{ message.sender.name }}
									<span class="mx-1">·</span>
									{{ message.created_at_formatted }}
								</div>

								<div :class="[
									'rounded-2xl text-sm break-words overflow-hidden',
									message.sender.id === user.id
										? 'bg-primary text-primary-foreground'
										: 'bg-muted'
								]">
									<!-- Image attachment -->
									<a v-if="message.file && message.file.mime_type?.startsWith('image/')"
										:href="message.file.url" target="_blank" rel="noopener noreferrer"
										class="block">
										<img :src="message.file.url" :alt="message.file.name"
											class="w-48 h-40 object-cover rounded-lg" />
									</a>

									<!-- Other file types -->
									<a v-else-if="message.file" :href="message.file.url" target="_blank"
										rel="noopener noreferrer" class="chat-file flex items-center gap-3 px-3.5 py-3">
										<div
											class="h-9 w-9 shrink-0 rounded-md bg-background/20 flex items-center justify-center">
											<PaperClipIcon class="h-4 w-4" />
										</div>

										<div class="chat-file-info">
											<div class="chat-file-name">
												{{ message.file.name }}
											</div>

											<div class="text-xs opacity-70">
												{{ formatFileSize(message.file.size) }}
											</div>
										</div>
									</a>

									<!-- Text -->
									<div v-if="message.message" class="px-3.5 py-2">
										{{ message.message }}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-if="selectedConversation" class="border-t p-3 space-y-2">
				<div v-if="sendError"
					class="flex items-center justify-between rounded-md bg-destructive/10 px-3 py-2 text-sm text-destructive">
					<span>{{ sendError }}</span>

					<button type="button" @click="sendError = ''" class="ml-2 text-xs font-medium hover:underline">
						Dismiss
					</button>
				</div>
				<div v-if="selectedFile" class="flex items-center gap-2 rounded-md bg-muted px-3 py-2 text-sm">
					<PaperClipIcon class="h-4 w-4 shrink-0" />

					<span class="min-w-0 flex-1 truncate">
						{{ selectedFile.name }}
					</span>

					<button v-if="!isSending" type="button" @click="selectedFile = null; fileInput.value = ''"
						class="text-muted-foreground hover:text-foreground">
						×
					</button>

					<svg v-else class="h-4 w-4 shrink-0 animate-spin text-muted-foreground" viewBox="0 0 24 24"
						fill="none">
						<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" class="opacity-25" />
						<path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
					</svg>
				</div>

				<div class="flex gap-2">
					<button type="button" @click="openFilePicker" :disabled="isSending || isSelectingFile"
						class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9">
						<svg v-if="isSelectingFile" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
							viewBox="0 0 24 24">
							<title xmlns="">270-ring</title>
							<path fill="currentColor"
								d="M10.72,19.9a8,8,0,0,1-6.5-9.79A7.77,7.77,0,0,1,10.4,4.16a8,8,0,0,1,9.49,6.52A1.54,1.54,0,0,0,21.38,12h.13a1.37,1.37,0,0,0,1.38-1.54,11,11,0,1,0-12.7,12.39A1.54,1.54,0,0,0,12,21.34h0A1.47,1.47,0,0,0,10.72,19.9Z">
								<animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite"
									type="rotate" values="0 12 12;360 12 12" />
							</path>
						</svg>

						<PaperClipIcon v-else />
					</button>

					<input ref="fileInput" type="file" class="absolute w-px h-px opacity-0"
						@click="isSelectingFile = true" @change="handleFileSelected" @cancel="handleFileCancelled" />

					<input v-model="newMessage" @keydown.enter.prevent="sendMessage" :disabled="isSending"
						class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
						:placeholder="`Message ${selectedConversation.name}`" />

					<button @click="sendMessage" :disabled="isSending || (!newMessage.trim() && !selectedFile)"
						class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
						<svg v-if="isSending" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
							viewBox="0 0 24 24">
							<title xmlns="">270-ring</title>
							<path fill="currentColor"
								d="M10.72,19.9a8,8,0,0,1-6.5-9.79A7.77,7.77,0,0,1,10.4,4.16a8,8,0,0,1,9.49,6.52A1.54,1.54,0,0,0,21.38,12h.13a1.37,1.37,0,0,0,1.38-1.54,11,11,0,1,0-12.7,12.39A1.54,1.54,0,0,0,12,21.34h0A1.47,1.47,0,0,0,10.72,19.9Z">
								<animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite"
									type="rotate" values="0 12 12;360 12 12" />
							</path>
						</svg>

						<PlaneIcon v-else />
					</button>
				</div>
			</div>
		</div>

		<GroupChatDialog :open="showNewGroupChatDialog" @created="handleGroupCreated"
			@close="showNewGroupChatDialog = false" />

		<DirectChatDialog :open="showNewDirectChatDialog" @created="handleConversationCreated"
			@close="showNewDirectChatDialog = false" />

		<ChatMembersDialog :open="showChatMembersDialog" :conversation="selectedConversation" :members="members"
			@close="showChatMembersDialog = false" @member-removed="loadConversation(selectedConversation)"
			@member-added="loadConversation(selectedConversation)" />

		<GroupChatRenameDialog :open="showGroupRenameDialog" :conversation="selectedConversation"
			@updated="handleGroupRenamed" @close="showGroupRenameDialog = false" />
	</div>
</template>
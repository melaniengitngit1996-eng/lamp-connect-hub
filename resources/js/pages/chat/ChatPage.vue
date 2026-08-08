<script setup>
import { ref, onMounted, nextTick } from 'vue'

import GroupChatDialog from '../../pages/chat/GroupChatDialog.vue'
import DirectChatDialog from '../../pages/chat/DirectChatDialog.vue'
import { useAuth } from '../../stores/auth'
import ChannelIcon from '../../icons/ChannelIcon.vue'
import PlusIcon from '../../icons/PlusIcon.vue'
import PaperClipIcon from '../../icons/PaperClipIcon.vue'
import PlaneIcon from '../../icons/PlaneIcon.vue'
import GroupIcon from '../../icons/GroupIcon.vue'

const { logout, user, fetchUser, can } = useAuth()

const showNewGroupChatDialog = ref(false)
const showNewDirectChatDialog = ref(false)

const openNewGroupChatDialog = (item, type) => {
    showNewGroupChatDialog.value = true
}

const openNewDirectChatDialog = (item, type) => {
    showNewDirectChatDialog.value = true
}

const channels = ref([])
const groups = ref([])
const selectedConversation = ref(null)
const messages = ref([])
const directMessages = ref([])
const newMessage = ref('')
const messagesContainer = ref(null)

const loadChats = async () => {
    try {
        const { data } = await axios.get('/api/chat')

        channels.value = data.channels
        groups.value = data.groups
        directMessages.value = data.direct_messages

        // Select the first conversation
        const firstConversation =
            channels.value[0] ??
            groups.value[0] ??
            directMessages.value[0]

        if (firstConversation) {
            await loadConversation(firstConversation)
        }
    } catch (error) {
        console.error(error)
    }
}

const loadConversation = async (conversation) => {
    try {
        const { data } = await axios.get(
            `/api/chat/conversations/${conversation.id}`
        )

        selectedConversation.value = data.conversation
		console.log(selectedConversation.value);
        messages.value = data.messages.data ?? data.messages
    } catch (error) {
        console.error(error)
    }
}

const sendMessage = async () => {

    if (!newMessage.value.trim()) {
        return
    }

    const { data } = await axios.post(
        `/api/chat/conversations/${selectedConversation.value.id}/messages`,
        {
            message: newMessage.value
        }
    )

    messages.value.push(data.data)

    newMessage.value = ''

	await scrollToBottom()
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

onMounted(() => {
    loadChats()
})
</script>

<template>
<div class="h-screen flex">
	<aside class="w-72 border-r flex flex-col bg-card/30">
		<div class="px-4 py-4 border-b">
			<h1 class="font-display text-xl">Messages</h1>
			<p class="text-xs text-muted-foreground">Group chats</p>
		</div>
		<div class="flex-1 overflow-y-auto px-2 py-3 space-y-5">
			<div>
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
			</div>
			<div>
				<div class="flex items-center justify-between px-2 mb-1">
					<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Group chats</span>
					<button @click="openNewGroupChatDialog" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-6 w-6">
						<PlusIcon />
					</button>
				</div>
				<ul v-if="groups.length" class="space-y-0.5">
					<li v-for="group in groups"
						:key="group.id">
						<button
							@click="loadConversation(group)"
							:class="[
								'w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition',
								selectedConversation?.id === group.id && 'bg-accent'
							]"
						>
							<div class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
								<GroupIcon />
							</div>
							<span class="text-sm truncate flex-1">{{ group.name }}</span>
						</button>
					</li>
				</ul>
				<div v-else class="italic px-2 py-3 text-center text-muted-foreground text-xs">
					No group chats yet
				</div>
			</div>
			<div>
				<div class="flex items-center justify-between px-2 mb-1">
					<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Direct messages</span>
					<button @click="openNewDirectChatDialog" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-6 w-6">
						<PlusIcon />
					</button>
				</div>
				<ul v-if="directMessages.length" class="space-y-0.5">
					<li v-for="directMessage in directMessages"
						:key="directMessage.id">
						<button
							@click="loadConversation(directMessage)"
							:class="[
								'w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition',
								selectedConversation?.id === directMessage.id && 'bg-accent'
							]"
						>
							<span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9">
								<span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">
									{{ directMessage.initials }}
								</span>
							</span>
							<span class="text-sm truncate flex-1">{{ directMessage.name }}</span>
						</button>
					</li>
				</ul>
				<div v-else class="italic px-2 py-3 text-center text-muted-foreground text-xs">
					No conversations yet.
				</div>
			</div>
		</div>
	</aside>

	<div class="flex-1 flex flex-col min-w-0">
		<header class="px-6 py-4 border-b flex items-center gap-3">
			<div v-if="selectedConversation?.type != 'direct'" class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
				<GroupIcon />
			</div>
			<span v-else class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9">
				<span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">
					{{ selectedConversation?.initials }}
				</span>
			</span>
			<div class="min-w-0">
				<div class="font-display text-lg truncate">{{ selectedConversation?.name }}</div>
				<div class="text-xs text-muted-foreground" v-if="selectedConversation?.type !== 'direct'">{{ selectedConversation?.members_count }} members</div>
			</div>
			<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs ml-auto">
				<GroupIcon /> Members
			</button>
		</header>
		<div ref="messagesContainer" class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
			<div
				v-if="!messages.length"
				class="h-full flex items-center justify-center text-muted-foreground"
			>
				No messages yet.
				Start the conversation.
			</div>
			<div
				v-else
				v-for="message in messages"
				:key="message.id"
				:class="[
					'flex gap-2',
					message.sender.id === user.id
						? 'flex-row-reverse'
						: ''
				]"
			>
				<span class="relative flex overflow-hidden rounded-full h-8 w-8 shrink-0">
					<span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">
						{{ message.sender.initials }}
					</span>
				</span>

				<div
					:class="[
						'max-w-[75%] flex flex-col gap-1',
						message.sender.id === user.id
							? 'items-end'
							: 'items-start'
					]"
				>
					<div class="text-xs text-muted-foreground">
						{{ message.sender.name }}
					</div>

					<div
						:class="[
							'rounded-2xl px-3.5 py-2 text-sm break-words',
							message.sender.id === user.id
								? 'bg-primary text-primary-foreground'
								: 'bg-muted'
						]"
					>
						{{ message.message }}
					</div>
				</div>
			</div>
		</div>
		<div class="border-t p-3 space-y-2">
			<div class="flex gap-2">
				<!-- <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9">
					<PaperClipIcon />
				</button> -->
				<input class="hidden" type="file" /><input
					v-model="newMessage"
					@keydown.enter.prevent="sendMessage"
					class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
					:placeholder="selectedConversation
					? `Message ${selectedConversation.name}`
					: 'Type a message...'"
					value=""
				/>
				<button
					@click="sendMessage"
					class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2"
				>
					<PlaneIcon />
				</button>
			</div>
		</div>
	</div>

    <GroupChatDialog
        :open="showNewGroupChatDialog"
		@created="handleGroupCreated"
        @close="showNewGroupChatDialog = false"
    />

    <DirectChatDialog
        :open="showNewDirectChatDialog"
		@created="handleConversationCreated"
        @close="showNewDirectChatDialog = false"
    />
</div>
</template>
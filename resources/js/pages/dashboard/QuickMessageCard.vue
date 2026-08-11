<script setup>
import axios from 'axios'
import { onMounted, ref, reactive } from 'vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import { useSettings } from '../../stores/settings'
const settings = useSettings()

const posts = ref([])

const comments = reactive({})
const commentForms = reactive({})

const form = ref({
    content: '',
    link: '',
})

const loading = ref(false)

const emit = defineEmits(['countChanged'])

async function loadPosts() {
    const { data } = await axios.get('/api/posts')
    posts.value = data
    emit('countChanged', posts.value.length)
}

async function loadComments(post) {
    if (comments[post.id]) {
        delete comments[post.id]
        return
    }

    const { data } = await axios.get(`/api/posts/${post.id}/comments`)

    comments[post.id] = data
    commentForms[post.id] = ''
}

async function addComment(post) {
    const content = commentForms[post.id]?.trim()

    if (!content) return

    await axios.post(`/api/posts/${post.id}/comments`, {
        content,
    })

    commentForms[post.id] = ''

    const { data } = await axios.get(`/api/posts/${post.id}/comments`)

    comments[post.id] = data

    post.comments = data.length
}

async function submitPost() {
    if (!form.value.content.trim()) return

    loading.value = true

    try {
        await axios.post('/api/posts', form.value)

        form.value.content = ''
        form.value.link = ''

        await loadPosts()
    } finally {
        loading.value = false
    }
}

async function deletePost(id) {
    if (!confirm('Delete this post?')) {
        return
    }

    await axios.delete(`/api/posts/${id}`)
    await loadPosts()
}

async function toggleLike(post) {
    const { data } = await axios.post(`/api/posts/${post.id}/like`)

    post.likes = data.likes
    post.liked = data.liked
}

async function deleteComment(post, comment) {
    if (!confirm('Delete this comment?')) {
        return
    }

    await axios.delete(`/api/comments/${comment.id}`)

    comments[post.id] = comments[post.id].filter(
        c => c.id !== comment.id
    )

    post.comments--
}

onMounted(loadPosts)
onMounted(() => {
    settings.load()
})
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-5 sm:p-6 lg:col-span-8 lg:row-span-2"
        data-tsd-source="/src/routes/_app.feed.tsx:330:5">
        <div class="flex items-center gap-3 mb-4" data-tsd-source="/src/routes/_app.feed.tsx:168:5">
            <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0"
                data-tsd-source="/src/routes/_app.feed.tsx:169:7">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-message-circle h-4 w-4" aria-hidden="true"
                    data-tsd-source="/src/routes/_app.feed.tsx:170:9">
                    <path
                        d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719">
                    </path>
                </svg>
            </div>
            <div class="min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:172:7">
                <h2 class="font-display text-lg leading-tight truncate"
                    data-tsd-source="/src/routes/_app.feed.tsx:173:9">Quick Message</h2>
                <p class="text-xs text-muted-foreground truncate" data-tsd-source="/src/routes/_app.feed.tsx:174:22">
                    Share with the LAMP family</p>
            </div>
        </div>
        <div v-if="settings.feed.feed_posting_enabled" class="rounded-xl border bg-card/50 p-3 space-y-3">
            <textarea v-model="form.content"
                class="bg-transparent border border-input border-none disabled:cursor-not-allowed disabled:opacity-50 flex focus-visible:outline-none focus-visible:ring-0 md:text-sm min-h-[70px] placeholder:text-muted-foreground px-3 py-2 resize-none rounded-md shadow-sm text-base w-full"
                placeholder="Share an update, a verse, a prayer request..."></textarea>
            <input v-model="form.link"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm"
                placeholder="https://...">
            <div class="flex items-center justify-between pt-2 border-t"
                data-tsd-source="/src/routes/_app.feed.tsx:426:7">
                <div class="flex gap-1" data-tsd-source="/src/routes/_app.feed.tsx:427:9">
                    <button
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs"
                        data-tsd-source="/src/routes/_app.feed.tsx:431:11">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-link h-4 w-4 mr-1" aria-hidden="true"
                            data-tsd-source="/src/routes/_app.feed.tsx:432:13">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                        Link
                    </button>
                </div>
                <button @click="submitPost" :disabled="loading"
                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs disabled:opacity-50">
                    {{ loading ? 'Posting...' : 'Post' }}
                </button>
            </div>
        </div>
        <div class="mt-5 space-y-4">
            <div v-if="posts.length" v-for="post in posts" :key="post.id"
                class="rounded-xl border p-4 space-y-3 bg-card">
                <div class="flex items-start gap-3">
                    <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0">
                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                            {{ post.initials }}
                        </span>
                    </span>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-sm truncate">
                                {{ post.author }}
                            </span>

                            <span class="text-xs text-muted-foreground">
                                · {{ post.created_at }}
                            </span>
                        </div>
                    </div>

                    <button v-if="post.can_delete" @click="deletePost(post.id)"
                        class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-accent">
                        <TrashIcon class="h-4 w-4 text-muted-foreground" />
                    </button>
                </div>

                <p class="whitespace-pre-wrap text-[15px] leading-relaxed">
                    {{ post.content }}
                </p>

                <a v-if="post.link" :href="post.link" target="_blank"
                    class="block border rounded-lg p-3 hover:bg-accent transition text-sm">
                    <div class="text-xs text-muted-foreground">
                        Link
                    </div>

                    <div class="truncate font-medium mt-1">
                        {{ post.link }}
                    </div>
                </a>

                <div class="flex items-center gap-1 pt-2 border-t">
                    <button @click="toggleLike(post)"
                        class="inline-flex items-center justify-center h-8 rounded-md px-3 text-xs hover:bg-accent">
                        ❤️ {{ post.likes }}
                    </button>

                    <button v-if="settings.feed.feed_comments_enabled" @click="loadComments(post)"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap h-8 rounded-md px-3 text-xs hover:bg-accent">
                        💬 {{ post.comments }}
                    </button>
                </div>

                <div v-if="comments[post.id] && settings.feed.feed_comments_enabled" class="border-t pt-4 space-y-4">
                    <div class="flex gap-2">
                        <input v-model="commentForms[post.id]" type="text"
                            class="flex-1 rounded-md border px-3 py-2 text-sm" placeholder="Write a comment..."
                            @keyup.enter="addComment(post)">

                        <button @click="addComment(post)"
                            class="px-3 py-2 rounded-md bg-primary text-primary-foreground text-sm">
                            Send
                        </button>
                    </div>

                    <div v-for="comment in comments[post.id]" :key="comment.id" class="flex gap-3">
                        <div
                            class="h-8 w-8 rounded-full bg-muted flex items-center justify-center text-xs font-medium shrink-0">
                            {{ comment.initials }}
                        </div>

                        <div class="flex-1">
                            <div class="rounded-lg bg-muted p-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-sm">
                                        {{ comment.author }}
                                    </span>

                                    <span class="text-xs text-muted-foreground">
                                        {{ comment.created_at }}
                                    </span>

                                    <button v-if="comment.can_delete" @click="deleteComment(post, comment)"
                                        class="ml-auto text-xs text-destructive hover:underline">
                                        <TrashIcon />
                                    </button>
                                </div>

                                <div class="mt-1 text-sm whitespace-pre-wrap">
                                    {{ comment.content }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="py-8 text-center text-sm text-muted-foreground">
                No posts available.
            </div>
        </div>
    </div>
</template>
<script setup>
import { useAuth } from '../../stores/auth'

import { ref, onMounted } from 'vue'
import EventsCard from './EventsCard.vue';
import NewsCard from './NewsCard.vue';
import MusicCard from './MusicCard.vue';
import TestimoniesCard from './TestimoniesCard.vue';

const { can } = useAuth();

const activeTab = ref('events')
</script>

<template>
<main class="flex-1 min-w-0 pb-16 md:pb-0" data-tsd-source="/src/components/app-shell.tsx:99:7">
	<div class="max-w-5xl mx-auto px-4 py-8 space-y-6" data-tsd-source="/src/routes/_app.admin.content.tsx:55:5">
		<header data-tsd-source="/src/routes/_app.admin.content.tsx:56:7">
			<h1 class="font-display text-3xl flex items-center gap-2" data-tsd-source="/src/routes/_app.admin.content.tsx:57:9">
				Content
			</h1>
			<p class="text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.content.tsx:60:9">Manage events, announcements, LAMP music, and testimonies shown on the community feed.</p>
		</header>
		<div dir="ltr" data-orientation="horizontal">
			<div 
                role="tablist" 
                aria-orientation="horizontal" 
                class="inline-flex h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground" 
                tabindex="0" 
                data-orientation="horizontal" 
                style="outline: none;"
            >
                <button
                    @click="activeTab = 'events'"
                    :class="[
                        'inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background cursor-pointer transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed gap-1',
                        activeTab === 'events'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:69:15">
						<path d="M8 2v4"></path>
						<path d="M16 2v4"></path>
						<rect width="18" height="18" x="3" y="4" rx="2"></rect>
						<path d="M3 10h18"></path>
					</svg>
					Events
				</button>
				<button
                    @click="activeTab = 'news'"
                    :class="[
                        'inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background cursor-pointer transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed gap-1',
                        activeTab === 'news'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:69:15">
						<path d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"></path>
						<path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14"></path>
						<path d="M8 6v8"></path>
					</svg>
					News &amp; Announcements
				</button>
				<button
                    @click="activeTab = 'music'"
                    :class="[
                        'inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background cursor-pointer transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed gap-1',
                        activeTab === 'music'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-music h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:69:15">
						<path d="M9 18V5l12-2v13"></path>
						<circle cx="6" cy="18" r="3"></circle>
						<circle cx="18" cy="16" r="3"></circle>
					</svg>
					LAMP Music
				</button>
				<button
                    @click="activeTab = 'testimonies'"
                    :class="[
                        'inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium ring-offset-background cursor-pointer transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed gap-1',
                        activeTab === 'testimonies'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-quote h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:69:15">
						<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"></path>
						<path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"></path>
					</svg>
					Testimonies
				</button>
			</div>
			<div v-if="activeTab === 'events'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
				<EventsCard />
			</div>
			<div v-if="activeTab === 'news'"  class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <NewsCard />
            </div>
			<div v-if="activeTab === 'music'"  class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <MusicCard />
            </div>
			<div v-if="activeTab === 'testimonies'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <TestimoniesCard />
            </div>
		</div>
	</div>
</main>
</template>
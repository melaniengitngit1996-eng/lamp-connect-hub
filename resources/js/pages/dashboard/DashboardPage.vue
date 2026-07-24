<script setup>
import CompositionsCard from './CompositionsCard.vue';
import HighlightsCard from './HighlightsCard.vue';
import NewsCard from './NewsCard.vue';
import QuickMessageCard from './QuickMessageCard.vue';
import TestimoniesCard from './TestimoniesCard.vue';
</script>

<template>
<main class="flex-1 min-w-0 pb-16 md:pb-0" data-tsd-source="/src/components/app-shell.tsx:98:7">
	<div class="max-w-7xl mx-auto px-4 py-8 space-y-6" data-tsd-source="/src/routes/_app.feed.tsx:113:5">
		<header class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2" data-tsd-source="/src/routes/_app.feed.tsx:114:7">
			<div data-tsd-source="/src/routes/_app.feed.tsx:115:9">
				<h1 class="font-display text-3xl sm:text-4xl tracking-tight" data-tsd-source="/src/routes/_app.feed.tsx:116:11">Community</h1>
				<p class="text-sm text-muted-foreground mt-1" data-tsd-source="/src/routes/_app.feed.tsx:117:11">Events, announcements, testimonies, and life together — LAMP family.</p>
			</div>
			<div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 w-fit gap-1" data-tsd-source="/src/routes/_app.feed.tsx:121:9">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles h-3 w-3" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:122:11">
					<path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"></path>
					<path d="M20 2v4"></path>
					<path d="M22 4h-4"></path>
					<circle cx="4" cy="20" r="2"></circle>
				</svg>
				19 posts
			</div>
		</header>
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-5">
			<!-- Left Column -->
			<div class="lg:col-span-8 space-y-4 lg:space-y-5">
				<HighlightsCard />
				<QuickMessageCard />
			</div>

			<!-- Right Column -->
			<div class="lg:col-span-4 space-y-4 lg:space-y-5">
				<NewsCard />
				<CompositionsCard />
				<TestimoniesCard />
			</div>

		</div>
	</div>
</main>
</template>
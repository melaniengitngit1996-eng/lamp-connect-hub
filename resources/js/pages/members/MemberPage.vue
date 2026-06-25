<script setup></script>
<template>
<div class="max-w-3xl mx-auto px-4 py-8 space-y-8">
	<header>
		<h1 class="font-display text-3xl">Members</h1>
		<p class="text-sm text-muted-foreground">Review and approve new sign-ups.</p>
	</header>
	<section class="space-y-3">
		<h2 class="text-sm font-medium uppercase text-muted-foreground tracking-wide">Pending (0)</h2>
		<div class="rounded-xl border bg-card shadow p-6 text-sm text-center text-muted-foreground">No pending requests.</div>
	</section>
	<section class="space-y-3">
		<h2 class="text-sm font-medium uppercase text-muted-foreground tracking-wide">All members (5)</h2>
		<div class="rounded-xl border bg-card text-card-foreground shadow divide-y">
			<div class="flex items-center gap-3 px-4 py-3">
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">SC</span></span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">Sarah Chen</div>
					<div class="text-xs text-muted-foreground truncate">sarah.chen@example.com · joined 10h</div>
				</div>
				<div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80">approved</div>
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
						<path d="M18 6 6 18"></path>
						<path d="m6 6 12 12"></path>
					</svg>
				</button>
			</div>
			<div class="flex items-center gap-3 px-4 py-3">
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">DK</span></span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">David Kim</div>
					<div class="text-xs text-muted-foreground truncate">david.kim@example.com · joined 10h</div>
				</div>
				<div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80">approved</div>
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
						<path d="M18 6 6 18"></path>
						<path d="m6 6 12 12"></path>
					</svg>
				</button>
			</div>
			<div class="flex items-center gap-3 px-4 py-3">
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">RB</span></span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">Rachel Brooks</div>
					<div class="text-xs text-muted-foreground truncate">rachel.brooks@example.com · joined 10h</div>
				</div>
				<div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80">approved</div>
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
						<path d="M18 6 6 18"></path>
						<path d="m6 6 12 12"></path>
					</svg>
				</button>
			</div>
			<div class="flex items-center gap-3 px-4 py-3">
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">PM</span></span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">Pastor Mike</div>
					<div class="text-xs text-muted-foreground truncate">pastor.mike@example.com · joined 10h</div>
				</div>
				<div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80">approved</div>
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
						<path d="M18 6 6 18"></path>
						<path d="m6 6 12 12"></path>
					</svg>
				</button>
			</div>
			<div class="flex items-center gap-3 px-4 py-3">
				<span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full"><img class="aspect-square h-full w-full" src="https://lh3.googleusercontent.com/a/ACg8ocJvyec3OlWtfxjG3D5RG9t3tain8GGu6KW5yqx3Jg5V6uoylENt=s96-c"></span>
				<div class="flex-1 min-w-0">
					<div class="text-sm font-medium truncate">Melanie Ngitngit</div>
					<div class="text-xs text-muted-foreground truncate">melanie.ngitngit1996@gmail.com · joined 16/6/2026</div>
				</div>
				<div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80">approved</div>
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
						<path d="M18 6 6 18"></path>
						<path d="m6 6 12 12"></path>
					</svg>
				</button>
			</div>
		</div>
	</section>
</div>
</template>
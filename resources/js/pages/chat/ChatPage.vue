<script setup></script>

<template>
<div class="h-screen flex">
	<aside class="w-72 border-r flex flex-col bg-card/30">
		<div class="px-4 py-4 border-b">
			<h1 class="font-display text-xl">Messages</h1>
			<p class="text-xs text-muted-foreground">Personal &amp; group chats</p>
		</div>
		<div class="flex-1 overflow-y-auto px-2 py-3 space-y-5">
			<div>
				<div class="flex items-center justify-between px-2 mb-1"><span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Channels</span></div>
				<ul class="space-y-0.5">
					<li>
						<button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition">
							<div class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash h-4 w-4" aria-hidden="true">
									<line x1="4" x2="20" y1="9" y2="9"></line>
									<line x1="4" x2="20" y1="15" y2="15"></line>
									<line x1="10" x2="8" y1="3" y2="21"></line>
									<line x1="16" x2="14" y1="3" y2="21"></line>
								</svg>
							</div>
							<span class="text-sm truncate flex-1">Community</span>
						</button>
					</li>
				</ul>
			</div>
			<div>
				<div class="flex items-center justify-between px-2 mb-1">
					<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Group chats</span>
					<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-6 w-6">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-3.5 w-3.5" aria-hidden="true">
							<path d="M5 12h14"></path>
							<path d="M12 5v14"></path>
						</svg>
					</button>
				</div>
				<ul class="space-y-0.5">
					<li>
						<button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition">
							<div class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4" aria-hidden="true">
									<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
									<path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
									<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
									<circle cx="9" cy="7" r="4"></circle>
								</svg>
							</div>
							<span class="text-sm truncate flex-1">Youth Leaders</span>
						</button>
					</li>
					<li>
						<button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition">
							<div class="h-9 w-9 rounded-full bg-muted grid place-items-center text-muted-foreground">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-4 w-4" aria-hidden="true">
									<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
									<path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
									<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
									<circle cx="9" cy="7" r="4"></circle>
								</svg>
							</div>
							<span class="text-sm truncate flex-1">Worship Team</span>
						</button>
					</li>
				</ul>
			</div>
			<div>
				<div class="flex items-center justify-between px-2 mb-1">
					<span class="text-[11px] uppercase tracking-wide text-muted-foreground font-medium">Direct messages</span>
					<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-6 w-6">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-3.5 w-3.5" aria-hidden="true">
							<path d="M5 12h14"></path>
							<path d="M12 5v14"></path>
						</svg>
					</button>
				</div>
				<ul class="space-y-0.5">
					<li><button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition bg-accent"><span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">SC</span></span><span class="text-sm truncate flex-1">Sarah Chen</span></button></li>
					<li><button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition"><span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">SC</span></span><span class="text-sm truncate flex-1">Sarah Chen</span></button></li>
					<li><button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition"><span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">DK</span></span><span class="text-sm truncate flex-1">David Kim</span></button></li>
					<li><button class="w-full flex items-center gap-2.5 rounded-md px-2 py-1.5 text-left hover:bg-accent transition"><span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">PM</span></span><span class="text-sm truncate flex-1">Pastor Mike</span></button></li>
				</ul>
			</div>
		</div>
	</aside>
	<div class="flex-1 flex flex-col min-w-0">
		<header class="px-6 py-4 border-b flex items-center gap-3">
			<span class="relative flex shrink-0 overflow-hidden rounded-full h-9 w-9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">SC</span></span>
			<div class="min-w-0">
				<div class="font-display text-lg truncate">Sarah Chen</div>
				<div class="text-xs text-muted-foreground">Direct message</div>
			</div>
		</header>
		<div class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
			<div class="flex gap-2 ">
				<span class="relative flex overflow-hidden rounded-full h-8 w-8 shrink-0"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">SC</span></span>
				<div class="max-w-[75%] items-start flex flex-col gap-1">
					<div class="text-xs text-muted-foreground "><span class="font-medium">Sarah Chen</span> · 1d</div>
					<div class="rounded-2xl px-3.5 py-2 text-sm whitespace-pre-wrap break-words bg-muted">Rachel, can you cover greeting team this weekend?</div>
				</div>
			</div>
			<div class="flex gap-2 ">
				<span class="relative flex overflow-hidden rounded-full h-8 w-8 shrink-0"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-xs">RB</span></span>
				<div class="max-w-[75%] items-start flex flex-col gap-1">
					<div class="text-xs text-muted-foreground "><span class="font-medium">Rachel Brooks</span> · 1d</div>
					<div class="rounded-2xl px-3.5 py-2 text-sm whitespace-pre-wrap break-words bg-muted">Of course! Happy to help.</div>
				</div>
			</div>
            <div class="flex gap-2 flex-row-reverse">
                <span class="relative flex overflow-hidden rounded-full h-8 w-8 shrink-0"><img class="aspect-square h-full w-full" src="https://lh3.googleusercontent.com/a/ACg8ocJvyec3OlWtfxjG3D5RG9t3tain8GGu6KW5yqx3Jg5V6uoylENt=s96-c"></span>
                <div class="max-w-[75%] items-end flex flex-col gap-1">
                    <div class="text-xs text-muted-foreground text-right"><span class="font-medium">You</span> · 2d</div>
                    <div class="rounded-2xl px-3.5 py-2 text-sm whitespace-pre-wrap break-words bg-primary text-primary-foreground">Love it. I'll prep the opening game.</div>
                    <button class="text-[10px] text-muted-foreground hover:text-destructive flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-3 w-3" aria-hidden="true">
                            <path d="M10 11v6"></path>
                            <path d="M14 11v6"></path>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                            <path d="M3 6h18"></path>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </div>
		</div>
		<div class="border-t p-3 space-y-2">
			<div class="flex gap-2">
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paperclip h-4 w-4" aria-hidden="true">
						<path d="m16 6-8.414 8.586a2 2 0 0 0 2.829 2.829l8.414-8.586a4 4 0 1 0-5.657-5.657l-8.379 8.551a6 6 0 1 0 8.485 8.485l8.379-8.551"></path>
					</svg>
				</button>
				<input class="hidden" type="file"><input class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" placeholder="Message Sarah Chen" value="">
				<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send h-4 w-4" aria-hidden="true">
						<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path>
						<path d="m21.854 2.147-10.94 10.939"></path>
					</svg>
				</button>
			</div>
		</div>
	</div>
</div>
</template>
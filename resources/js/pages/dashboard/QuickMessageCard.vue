<template>
<div class="rounded-xl border bg-card text-card-foreground shadow p-5 sm:p-6 lg:col-span-8 lg:row-span-2" data-tsd-source="/src/routes/_app.feed.tsx:330:5">
    <div class="flex items-center gap-3 mb-4" data-tsd-source="/src/routes/_app.feed.tsx:168:5">
        <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:169:7">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:170:9">
                <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
            </svg>
        </div>
        <div class="min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:172:7">
            <h2 class="font-display text-lg leading-tight truncate" data-tsd-source="/src/routes/_app.feed.tsx:173:9">Quick Message</h2>
            <p class="text-xs text-muted-foreground truncate" data-tsd-source="/src/routes/_app.feed.tsx:174:22">Share with the LAMP family</p>
        </div>
    </div>
    <div class="rounded-xl border bg-card/50 p-3 space-y-3" data-tsd-source="/src/routes/_app.feed.tsx:403:5">
        <textarea class="bg-transparent border border-input border-none disabled:cursor-not-allowed disabled:opacity-50 flex focus-visible:outline-none focus-visible:ring-0 focus-visible:ring-ring md:text-sm min-h-[70px] placeholder:text-muted-foreground px-0 px-3 py-2 resize-none rounded-md shadow-sm text-base w-full" placeholder="Share an update, a verse, a prayer request..." data-tsd-source="/src/routes/_app.feed.tsx:404:7" spellcheck="false"></textarea>
        <div class="flex items-center justify-between pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:426:7">
            <div class="flex gap-1" data-tsd-source="/src/routes/_app.feed.tsx:427:9">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:428:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paperclip h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:429:13">
                        <path d="m16 6-8.414 8.586a2 2 0 0 0 2.829 2.829l8.414-8.586a4 4 0 1 0-5.657-5.657l-8.379 8.551a6 6 0 1 0 8.485 8.485l8.379-8.551"></path>
                    </svg>
                    Files
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:431:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:432:13">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Link
                </button>
                <input multiple="" class="hidden" data-tsd-source="/src/routes/_app.feed.tsx:434:11" type="file">
            </div>
            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:445:9"> Post</button>
        </div>
    </div>
    <div class="mt-5 space-y-4" data-tsd-source="/src/routes/_app.feed.tsx:333:7">
        <div class="rounded-xl border p-4 space-y-3 bg-card" data-tsd-source="/src/routes/_app.feed.tsx:496:5">
            <div class="flex items-start gap-3" data-tsd-source="/src/routes/_app.feed.tsx:497:7">
                <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:498:9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted" data-tsd-source="/src/routes/_app.feed.tsx:500:11">SC</span></span>
                <div class="flex-1 min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:502:9">
                    <div class="flex items-center gap-2" data-tsd-source="/src/routes/_app.feed.tsx:503:11"><span class="font-medium text-sm truncate" data-tsd-source="/src/routes/_app.feed.tsx:504:13">Sarah Chen</span><span class="text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.feed.tsx:505:13">· 25/6/2026</span></div>
                </div>
            </div>
            <p class="whitespace-pre-wrap text-[15px] leading-relaxed" data-tsd-source="/src/routes/_app.feed.tsx:515:24">Just wanted to say how blessed I am by this community. No link, just gratitude ❤️</p>
            <div class="flex items-center gap-1 pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:551:7">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:552:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:553:11">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
                    </svg>
                    0
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:555:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:556:11">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    0
                </button>
            </div>
        </div>
        <div class="rounded-xl border p-4 space-y-3 bg-card" data-tsd-source="/src/routes/_app.feed.tsx:496:5">
            <div class="flex items-start gap-3" data-tsd-source="/src/routes/_app.feed.tsx:497:7">
                <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:498:9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted" data-tsd-source="/src/routes/_app.feed.tsx:500:11">PM</span></span>
                <div class="flex-1 min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:502:9">
                    <div class="flex items-center gap-2" data-tsd-source="/src/routes/_app.feed.tsx:503:11"><span class="font-medium text-sm truncate" data-tsd-source="/src/routes/_app.feed.tsx:504:13">Pastor Mike</span><span class="text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.feed.tsx:505:13">· 25/6/2026</span></div>
                </div>
            </div>
            <p class="whitespace-pre-wrap text-[15px] leading-relaxed" data-tsd-source="/src/routes/_app.feed.tsx:515:24">Devotional thought for the week — a short read worth your morning coffee ☕</p>
            <a href="https://www.desiringgod.org/articles" target="_blank" rel="noreferrer" class="block border rounded-lg p-3 hover:bg-accent transition text-sm" data-tsd-source="/src/routes/_app.feed.tsx:518:9">
                <div class="flex items-center gap-2 text-muted-foreground text-xs" data-tsd-source="/src/routes/_app.feed.tsx:524:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-3 w-3" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:525:13">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Link
                </div>
                <div class="truncate font-medium mt-1" data-tsd-source="/src/routes/_app.feed.tsx:527:11">https://www.desiringgod.org/articles</div>
            </a>
            <div class="flex items-center gap-1 pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:551:7">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:552:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:553:11">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
                    </svg>
                    0
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:555:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:556:11">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    0
                </button>
            </div>
        </div>
        <div class="rounded-xl border p-4 space-y-3 bg-card" data-tsd-source="/src/routes/_app.feed.tsx:496:5">
            <div class="flex items-start gap-3" data-tsd-source="/src/routes/_app.feed.tsx:497:7">
                <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:498:9"><img class="aspect-square h-full w-full" data-tsd-source="/src/routes/_app.feed.tsx:499:11" src="https://lh3.googleusercontent.com/a/ACg8ocJvyec3OlWtfxjG3D5RG9t3tain8GGu6KW5yqx3Jg5V6uoylENt=s96-c"></span>
                <div class="flex-1 min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:502:9">
                    <div class="flex items-center gap-2" data-tsd-source="/src/routes/_app.feed.tsx:503:11"><span class="font-medium text-sm truncate" data-tsd-source="/src/routes/_app.feed.tsx:504:13">Melanie Ngitngit</span><span class="text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.feed.tsx:505:13">· 25/6/2026</span></div>
                </div>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-9 w-9" data-tsd-source="/src/routes/_app.feed.tsx:509:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4 text-muted-foreground" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:510:13">
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </div>
            <p class="whitespace-pre-wrap text-[15px] leading-relaxed" data-tsd-source="/src/routes/_app.feed.tsx:515:24">Reminder: Youth night this Friday at 7pm. RSVP through the link below 🎉</p>
            <a href="https://forms.gle/youth-night-rsvp" target="_blank" rel="noreferrer" class="block border rounded-lg p-3 hover:bg-accent transition text-sm" data-tsd-source="/src/routes/_app.feed.tsx:518:9">
                <div class="flex items-center gap-2 text-muted-foreground text-xs" data-tsd-source="/src/routes/_app.feed.tsx:524:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-3 w-3" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:525:13">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Link
                </div>
                <div class="truncate font-medium mt-1" data-tsd-source="/src/routes/_app.feed.tsx:527:11">https://forms.gle/youth-night-rsvp</div>
            </a>
            <div class="flex items-center gap-1 pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:551:7">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:552:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:553:11">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
                    </svg>
                    0
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:555:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:556:11">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    0
                </button>
            </div>
        </div>
        <div class="rounded-xl border p-4 space-y-3 bg-card" data-tsd-source="/src/routes/_app.feed.tsx:496:5">
            <div class="flex items-start gap-3" data-tsd-source="/src/routes/_app.feed.tsx:497:7">
                <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:498:9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted" data-tsd-source="/src/routes/_app.feed.tsx:500:11">RB</span></span>
                <div class="flex-1 min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:502:9">
                    <div class="flex items-center gap-2" data-tsd-source="/src/routes/_app.feed.tsx:503:11"><span class="font-medium text-sm truncate" data-tsd-source="/src/routes/_app.feed.tsx:504:13">Rachel Brooks</span><span class="text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.feed.tsx:505:13">· 24/6/2026</span></div>
                </div>
            </div>
            <p class="whitespace-pre-wrap text-[15px] leading-relaxed" data-tsd-source="/src/routes/_app.feed.tsx:515:24">Great article on small group leadership I came across this morning ✨</p>
            <a href="https://www.christianitytoday.com/pastors/small-groups" target="_blank" rel="noreferrer" class="block border rounded-lg p-3 hover:bg-accent transition text-sm" data-tsd-source="/src/routes/_app.feed.tsx:518:9">
                <div class="flex items-center gap-2 text-muted-foreground text-xs" data-tsd-source="/src/routes/_app.feed.tsx:524:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-3 w-3" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:525:13">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Link
                </div>
                <div class="truncate font-medium mt-1" data-tsd-source="/src/routes/_app.feed.tsx:527:11">https://www.christianitytoday.com/pastors/small-groups</div>
            </a>
            <div class="flex items-center gap-1 pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:551:7">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:552:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:553:11">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
                    </svg>
                    0
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:555:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:556:11">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    0
                </button>
            </div>
        </div>
        <div class="rounded-xl border p-4 space-y-3 bg-card" data-tsd-source="/src/routes/_app.feed.tsx:496:5">
            <div class="flex items-start gap-3" data-tsd-source="/src/routes/_app.feed.tsx:497:7">
                <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:498:9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted" data-tsd-source="/src/routes/_app.feed.tsx:500:11">DK</span></span>
                <div class="flex-1 min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:502:9">
                    <div class="flex items-center gap-2" data-tsd-source="/src/routes/_app.feed.tsx:503:11"><span class="font-medium text-sm truncate" data-tsd-source="/src/routes/_app.feed.tsx:504:13">David Kim</span><span class="text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.feed.tsx:505:13">· 24/6/2026</span></div>
                </div>
            </div>
            <p class="whitespace-pre-wrap text-[15px] leading-relaxed" data-tsd-source="/src/routes/_app.feed.tsx:515:24">Updated the slides for this Sunday's service. Feedback welcome!</p>
            <a href="https://docs.google.com/presentation/d/1abc123" target="_blank" rel="noreferrer" class="block border rounded-lg p-3 hover:bg-accent transition text-sm" data-tsd-source="/src/routes/_app.feed.tsx:518:9">
                <div class="flex items-center gap-2 text-muted-foreground text-xs" data-tsd-source="/src/routes/_app.feed.tsx:524:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-3 w-3" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:525:13">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Link
                </div>
                <div class="truncate font-medium mt-1" data-tsd-source="/src/routes/_app.feed.tsx:527:11">https://docs.google.com/presentation/d/1abc123</div>
            </a>
            <div class="flex items-center gap-1 pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:551:7">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:552:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:553:11">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
                    </svg>
                    0
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:555:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:556:11">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    0
                </button>
            </div>
        </div>
        <div class="rounded-xl border p-4 space-y-3 bg-card" data-tsd-source="/src/routes/_app.feed.tsx:496:5">
            <div class="flex items-start gap-3" data-tsd-source="/src/routes/_app.feed.tsx:497:7">
                <span class="relative flex overflow-hidden rounded-full h-9 w-9 shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:498:9"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted" data-tsd-source="/src/routes/_app.feed.tsx:500:11">SC</span></span>
                <div class="flex-1 min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:502:9">
                    <div class="flex items-center gap-2" data-tsd-source="/src/routes/_app.feed.tsx:503:11"><span class="font-medium text-sm truncate" data-tsd-source="/src/routes/_app.feed.tsx:504:13">Sarah Chen</span><span class="text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.feed.tsx:505:13">· 23/6/2026</span></div>
                </div>
            </div>
            <p class="whitespace-pre-wrap text-[15px] leading-relaxed" data-tsd-source="/src/routes/_app.feed.tsx:515:24">Found this beautiful worship song for next week's set. What do you think team?</p>
            <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank" rel="noreferrer" class="block border rounded-lg p-3 hover:bg-accent transition text-sm" data-tsd-source="/src/routes/_app.feed.tsx:518:9">
                <div class="flex items-center gap-2 text-muted-foreground text-xs" data-tsd-source="/src/routes/_app.feed.tsx:524:11">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-3 w-3" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:525:13">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Link
                </div>
                <div class="truncate font-medium mt-1" data-tsd-source="/src/routes/_app.feed.tsx:527:11">https://www.youtube.com/watch?v=dQw4w9WgXcQ</div>
            </a>
            <div class="flex items-center gap-1 pt-2 border-t" data-tsd-source="/src/routes/_app.feed.tsx:551:7">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:552:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:553:11">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
                    </svg>
                    0
                </button>
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.feed.tsx:555:9">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:556:11">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path>
                    </svg>
                    0
                </button>
            </div>
        </div>
    </div>
</div>
</template>
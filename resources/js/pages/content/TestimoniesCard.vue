<script setup></script>

<template>
<div class="rounded-xl border bg-card text-card-foreground shadow p-0" data-tsd-source="/src/routes/_app.admin.content.tsx:120:5">
    <div class="flex items-center justify-between p-4 border-b gap-3" data-tsd-source="/src/routes/_app.admin.content.tsx:121:7">
        <div class="min-w-0" data-tsd-source="/src/routes/_app.admin.content.tsx:122:9">
            <div class="text-sm font-medium" data-tsd-source="/src/routes/_app.admin.content.tsx:123:11">Testimonies</div>
            <div class="text-xs text-muted-foreground truncate" data-tsd-source="/src/routes/_app.admin.content.tsx:124:11">Stories of how God is moving. · 3 entry(ies)</div>
        </div>
        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:126:9">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:127:11">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            New Testimonie
        </button>
    </div>
    <div class="relative w-full overflow-auto" data-tsd-source="/src/components/ui/table.tsx:7:5">
        <table class="w-full caption-bottom text-sm" data-tsd-source="/src/routes/_app.admin.content.tsx:135:9">
            <thead class="[&amp;_tr]:border-b" data-tsd-source="/src/routes/_app.admin.content.tsx:136:11">
                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.content.tsx:137:13">
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:138:15">Content</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:139:15">Link</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:140:15">Author</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:141:15">Posted</th>
                    <th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.content.tsx:142:15">Actions</th>
                </tr>
            </thead>
            <tbody class="[&amp;_tr:last-child]:border-0" data-tsd-source="/src/routes/_app.admin.content.tsx:145:11">
                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.content.tsx:147:15">
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] max-w-md" data-tsd-source="/src/routes/_app.admin.content.tsx:148:17">
                        <div class="text-sm line-clamp-2" data-tsd-source="/src/routes/_app.admin.content.tsx:149:19">God provided a new job for me after months of waiting. His timing is always perfect. Grateful to this church family for the prayers.</div>
                    </td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:151:17">—</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.content.tsx:158:17">David Kim</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.admin.content.tsx:159:17">3d</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.content.tsx:160:17">
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:161:19">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:162:21">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                <path d="m15 5 4 4"></path>
                            </svg>
                        </button>
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:164:19">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4 text-destructive" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:165:21">
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                <path d="M3 6h18"></path>
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.content.tsx:147:15">
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] max-w-md" data-tsd-source="/src/routes/_app.admin.content.tsx:148:17">
                        <div class="text-sm line-clamp-2" data-tsd-source="/src/routes/_app.admin.content.tsx:149:19">My mother's surgery went well and she is recovering faster than expected. Thank you for standing with us in prayer!</div>
                    </td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:151:17">—</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.content.tsx:158:17">Rachel Brooks</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.admin.content.tsx:159:17">5d</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.content.tsx:160:17">
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:161:19">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:162:21">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                <path d="m15 5 4 4"></path>
                            </svg>
                        </button>
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:164:19">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4 text-destructive" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:165:21">
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                <path d="M3 6h18"></path>
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.content.tsx:147:15">
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] max-w-md" data-tsd-source="/src/routes/_app.admin.content.tsx:148:17">
                        <div class="text-sm line-clamp-2" data-tsd-source="/src/routes/_app.admin.content.tsx:149:19">Financial breakthrough this week — an unexpected door opened. Truly, God sees and provides.</div>
                    </td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:151:17">—</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.content.tsx:158:17">Melanie Ngitngit</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-xs text-muted-foreground" data-tsd-source="/src/routes/_app.admin.content.tsx:159:17">17/7/2026</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.content.tsx:160:17">
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:161:19">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:162:21">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                <path d="m15 5 4 4"></path>
                            </svg>
                        </button>
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:164:19">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4 text-destructive" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:165:21">
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                <path d="M3 6h18"></path>
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>
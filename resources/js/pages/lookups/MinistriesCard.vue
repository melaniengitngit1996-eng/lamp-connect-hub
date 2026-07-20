<script setup>
import { useAuth } from '../../stores/auth'

const { can } = useAuth();

import { ref, onMounted } from 'vue'

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'
</script>

<template>
<div data-state="active" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-_r_u_-trigger-ministries" id="radix-_r_u_-content-ministries" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4" data-tsd-source="/src/routes/_app.admin.lookups.tsx:62:9">
	<div class="rounded-xl border bg-card text-card-foreground shadow p-0" data-tsd-source="/src/routes/_app.admin.lookups.tsx:116:5">
		<div class="flex items-center justify-between p-4 border-b gap-3" data-tsd-source="/src/routes/_app.admin.lookups.tsx:117:7">
			<div class="flex items-center gap-3" data-tsd-source="/src/routes/_app.admin.lookups.tsx:118:9">
				<div class="text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:119:11">12 entry(ies)</div>
				<button type="button" role="combobox" aria-controls="radix-_r_28_" aria-expanded="false" aria-autocomplete="none" dir="ltr" data-state="closed" class="flex items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background cursor-pointer data-[placeholder]:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&amp;&gt;span]:line-clamp-1 w-56 h-8" data-tsd-source="/src/routes/_app.admin.lookups.tsx:122:15">
					<span data-tsd-source="/src/routes/_app.admin.lookups.tsx:123:17" style="pointer-events: none;">All churches</span>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 opacity-50" aria-hidden="true">
						<path d="m6 9 6 6 6-6"></path>
					</svg>
				</button>
			</div>
			<button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.lookups.tsx:135:9">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.lookups.tsx:136:11">
					<path d="M5 12h14"></path>
					<path d="M12 5v14"></path>
				</svg>
				New Ministry
			</button>
		</div>
		<div class="relative w-full overflow-auto" data-tsd-source="/src/components/ui/table.tsx:7:5">
			<table class="w-full caption-bottom text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:144:9">
				<thead class="[&amp;_tr]:border-b" data-tsd-source="/src/routes/_app.admin.lookups.tsx:145:11">
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:146:13">
						<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.lookups.tsx:147:15">Name</th>
						<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.lookups.tsx:148:26">Local Church</th>
						<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.lookups.tsx:149:15">Description</th>
						<th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:150:15">Actions</th>
					</tr>
				</thead>
				<tbody class="[&amp;_tr:last-child]:border-0" data-tsd-source="/src/routes/_app.admin.lookups.tsx:153:11">
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Admin</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">Muntinlupa</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Creatives</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Discipleship</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Hospitality</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Kids</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Media</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Outreach</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Prayer</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Production</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Ushering</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Worship</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.lookups.tsx:155:15">
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium" data-tsd-source="/src/routes/_app.admin.lookups.tsx:156:17">Youth</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:158:19">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground" data-tsd-source="/src/routes/_app.admin.lookups.tsx:160:17">—</td>
						<td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.lookups.tsx:163:17">
							<Button v-if="can('lookups.update')" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</template>
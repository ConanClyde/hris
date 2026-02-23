<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Pencil, Trash2 } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';

type NoticeItem = {
    id: number;
    title: string;
    message: string;
    type: string;
    is_active: boolean;
    expires_at: string | null;
    created_at: string;
};

type PaginatedData = {
    data: NoticeItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

defineProps<{ notices: PaginatedData }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: admin.dashboard().url },
    { title: 'Global Notices' },
];

const createModalOpen = ref(false);
const editModalOpen = ref(false);
const editingNotice = ref<NoticeItem | null>(null);

const typeOptions = [
    { value: 'info', label: 'Info' },
    { value: 'success', label: 'Success' },
    { value: 'warning', label: 'Warning' },
    { value: 'danger', label: 'Danger' },
];

const createType = ref('info');
const createIsActive = ref(true);

const editTitle = ref('');
const editMessage = ref('');
const editType = ref('info');
const editIsActive = ref(true);
const editExpiresAt = ref('');

watch(editingNotice, (n) => {
    if (n) {
        editTitle.value = n.title;
        editMessage.value = n.message;
        editType.value = n.type || 'info';
        editIsActive.value = n.is_active ?? true;
        editExpiresAt.value = n.expires_at ? String(n.expires_at).slice(0, 10) : '';
    }
}, { immediate: true });

function openEdit(n: NoticeItem) {
    editingNotice.value = n;
    editModalOpen.value = true;
}

function closeEdit() {
    editModalOpen.value = false;
    editingNotice.value = null;
}

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}

function messageSnippet(msg: string, maxLen = 60) {
    if (!msg) return '—';
    return msg.length <= maxLen ? msg : msg.slice(0, maxLen) + '…';
}

function confirmDelete(message: string) {
    return window.confirm(message);
}

function typeVariant(type: string) {
    if (type === 'success') return 'default';
    if (type === 'danger') return 'destructive';
    if (type === 'warning') return 'secondary';
    return 'outline';
}
</script>

<template>
    <Head title="Global Notices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Global Notices
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Publish organization-wide announcements.
                    </p>
                </div>
                <Button type="button" @click="createModalOpen = true">Create Notice</Button>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Title</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Message</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Type</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Status</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Expires</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="notice in notices.data"
                                :key="notice.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ notice.title || '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
                                    {{ messageSnippet(notice.message) }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="typeVariant(notice.type)">{{ notice.type || 'info' }}</Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="notice.is_active ? 'default' : 'secondary'">
                                        {{ notice.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(notice.expires_at) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0"
                                            @click="openEdit(notice)"
                                        >
                                            <span class="sr-only">Edit</span>
                                            <Pencil class="size-4" />
                                        </Button>
                                        <Form
                                            :action="admin.notices.destroy.url(notice.id)"
                                            method="post"
                                            class="inline"
                                            @submit="(e: Event) => !confirmDelete('Delete this notice?') && e.preventDefault()"
                                        >
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <Button
                                                type="submit"
                                                variant="ghost"
                                                size="icon-sm"
                                                class="h-8 w-8 p-0 text-red-600 hover:text-red-700 dark:text-red-400"
                                            >
                                                <span class="sr-only">Delete</span>
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </Form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!notices.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No notices found.
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="notices.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in notices.links" :key="i">
                    <span
                        v-if="!link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 px-3 text-sm text-gray-400 dark:border-neutral-700"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm transition-colors"
                        :class="link.active
                            ? 'border-[#013CFC] bg-[#013CFC] text-white dark:border-[#60C8FC] dark:bg-[#60C8FC] dark:text-gray-900'
                            : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>

        <!-- Create notice modal -->
        <Dialog v-model:open="createModalOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Create Notice</DialogTitle>
                </DialogHeader>
                <Form
                    v-bind="admin.notices.store.form()"
                    class="flex flex-col gap-4"
                    @submit="createModalOpen = false"
                >
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                        <div class="grid gap-2">
                            <Label for="create-title">Title</Label>
                            <Input id="create-title" name="title" type="text" required placeholder="Notice title" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="create-message">Message / Body</Label>
                            <textarea
                                id="create-message"
                                name="message"
                                rows="5"
                                required
                                class="flex min-h-[100px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-[#013CFC] focus:outline-none focus:ring-1 focus:ring-[#013CFC] dark:border-gray-700 dark:bg-neutral-800 dark:placeholder:text-gray-500"
                                placeholder="Notice content"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="create-type">Type</Label>
                            <Select v-model="createType" name="type">
                                <SelectTrigger id="create-type">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="opt in typeOptions"
                                        :key="opt.value"
                                        :value="opt.value"
                                    >
                                        {{ opt.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <input type="hidden" name="type" :value="createType" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="create-expires_at">Expires at (optional)</Label>
                            <Input id="create-expires_at" name="expires_at" type="date" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0" />
                            <input
                                id="create-is_active"
                                v-model="createIsActive"
                                name="is_active"
                                type="checkbox"
                                value="1"
                                class="h-4 w-4 rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC] dark:border-gray-600 dark:bg-neutral-800"
                            />
                            <Label for="create-is_active" class="cursor-pointer">Active</Label>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="createModalOpen = false">
                            Cancel
                        </Button>
                        <Button type="submit">Create notice</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Edit notice modal -->
        <Dialog v-model:open="editModalOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Edit Notice</DialogTitle>
                </DialogHeader>
                <Form
                    v-if="editingNotice"
                    :action="admin.notices.update.url(editingNotice.id)"
                    method="post"
                    class="flex flex-col gap-4"
                    @submit="closeEdit()"
                >
                    <input type="hidden" name="_method" value="PUT" />
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                        <div class="grid gap-2">
                            <Label for="edit-title">Title</Label>
                            <Input
                                id="edit-title"
                                v-model="editTitle"
                                name="title"
                                type="text"
                                required
                                placeholder="Notice title"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-message">Message / Body</Label>
                            <textarea
                                id="edit-message"
                                v-model="editMessage"
                                name="message"
                                rows="5"
                                required
                                class="flex min-h-[100px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-[#013CFC] focus:outline-none focus:ring-1 focus:ring-[#013CFC] dark:border-gray-700 dark:bg-neutral-800 dark:placeholder:text-gray-500"
                                placeholder="Notice content"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-type">Type</Label>
                            <Select v-model="editType" name="type">
                                <SelectTrigger id="edit-type">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="opt in typeOptions"
                                        :key="opt.value"
                                        :value="opt.value"
                                    >
                                        {{ opt.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <input type="hidden" name="type" :value="editType" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-expires_at">Expires at (optional)</Label>
                            <Input id="edit-expires_at" v-model="editExpiresAt" name="expires_at" type="date" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0" />
                            <input
                                id="edit-is_active"
                                v-model="editIsActive"
                                name="is_active"
                                type="checkbox"
                                value="1"
                                class="h-4 w-4 rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC] dark:border-gray-600 dark:bg-neutral-800"
                            />
                            <Label for="edit-is_active" class="cursor-pointer">Active</Label>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeEdit()">
                            Cancel
                        </Button>
                        <Button type="submit">Update</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

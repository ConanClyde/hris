<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { Eye, Pencil, Trash2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
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
import hr from '@/routes/hr';
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
    { title: 'Notices' },
];

const createModalOpen = ref(false);
const editModalOpen = ref(false);
const viewModalOpen = ref(false);
const deleteModalOpen = ref(false);
const editingNotice = ref<NoticeItem | null>(null);
const viewingNotice = ref<NoticeItem | null>(null);
const deletingNotice = ref<NoticeItem | null>(null);

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
    viewModalOpen.value = false;
}

function closeEdit() {
    editModalOpen.value = false;
    editingNotice.value = null;
}

function openView(n: NoticeItem) {
    viewingNotice.value = n;
    viewModalOpen.value = true;
}

function closeView() {
    viewModalOpen.value = false;
    viewingNotice.value = null;
}

function openDeleteNotice(n: NoticeItem) {
    deletingNotice.value = n;
    deleteModalOpen.value = true;
}
function closeDeleteNotice() {
    deleteModalOpen.value = false;
    deletingNotice.value = null;
}
function confirmDeleteNotice() {
    if (!deletingNotice.value) return;
    router.delete(hr.notices.destroy.url(deletingNotice.value.id), { onSuccess: () => closeDeleteNotice() });
}

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}

function typeVariant(type: string) {
    if (type === 'success') return 'default';
    if (type === 'danger') return 'destructive';
    if (type === 'warning') return 'secondary';
    return 'outline';
}
</script>

<template>
    <Head title="Notices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Global Notices
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Create and manage notices for all users.
                    </p>
                </div>
                <Button @click="createModalOpen = true">Create notice</Button>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Title</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Type</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Status</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Expires</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="n in notices.data"
                                :key="n.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ n.title || '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="typeVariant(n.type)">{{ n.type || 'info' }}</Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="n.is_active ? 'default' : 'secondary'">
                                        {{ n.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(n.expires_at) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 text-muted-foreground"
                                            title="View"
                                            @click="openView(n)"
                                        >
                                            <Eye class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 text-muted-foreground hover:text-primary"
                                            title="Edit"
                                            @click="openEdit(n)"
                                        >
                                            <Pencil class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                            title="Delete"
                                            @click="openDeleteNotice(n)"
                                        >
                                            <Trash2 class="size-4" />
                                        </Button>
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
                    No notices yet. Create one to get started.
                </div>
            </div>

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
                        :class="link.active ? 'border-brand bg-brand text-white dark:border-brand-light dark:bg-brand-light dark:text-gray-900' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
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
                    <DialogTitle>Create notice</DialogTitle>
                    <DialogDescription class="sr-only">
                        Create a new HR notice for employees.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    v-bind="hr.notices.store.form()"
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
                                class="flex min-h-[100px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-gray-700 dark:bg-neutral-800 dark:placeholder:text-gray-500"
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
                                name="is_active"
                                type="checkbox"
                                v-model="createIsActive"
                                value="1"
                                class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand dark:border-gray-600 dark:bg-neutral-800"
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
                    <DialogTitle>Edit notice</DialogTitle>
                    <DialogDescription class="sr-only">
                        Edit an existing HR notice.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    v-if="editingNotice"
                    :action="hr.notices.update.url(editingNotice.id)"
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
                                name="title"
                                type="text"
                                required
                                v-model="editTitle"
                                placeholder="Notice title"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-message">Message / Body</Label>
                            <textarea
                                id="edit-message"
                                name="message"
                                rows="5"
                                required
                                v-model="editMessage"
                                class="flex min-h-[100px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-gray-700 dark:bg-neutral-800 dark:placeholder:text-gray-500"
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
                            <Input id="edit-expires_at" name="expires_at" type="date" v-model="editExpiresAt" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0" />
                            <input
                                id="edit-is_active"
                                name="is_active"
                                type="checkbox"
                                v-model="editIsActive"
                                value="1"
                                class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand dark:border-gray-600 dark:bg-neutral-800"
                            />
                            <Label for="edit-is_active" class="cursor-pointer">Active</Label>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeEdit">
                            Cancel
                        </Button>
                        <Button type="submit">Update</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- View Notice modal -->
        <Dialog v-model:open="viewModalOpen">
            <DialogContent v-if="viewingNotice" :show-close-button="true" class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>View Notice</DialogTitle>
                    <DialogDescription class="sr-only">
                        View details of the selected notice.
                    </DialogDescription>
                </DialogHeader>
                <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                    <div>
                        <h3 class="text-sm font-medium text-foreground">{{ viewingNotice.title || '—' }}</h3>
                        <p class="mt-1 text-sm text-muted-foreground whitespace-pre-wrap">{{ viewingNotice.message || '—' }}</p>
                    </div>
                    <dl class="grid grid-cols-1 gap-2 text-sm">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Type</dt>
                            <dd class="mt-0.5"><Badge :variant="typeVariant(viewingNotice.type)">{{ viewingNotice.type || 'info' }}</Badge></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Status</dt>
                            <dd class="mt-0.5"><Badge :variant="viewingNotice.is_active ? 'default' : 'secondary'">{{ viewingNotice.is_active ? 'Active' : 'Inactive' }}</Badge></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Expires at</dt>
                            <dd class="mt-0.5">{{ formatDate(viewingNotice.expires_at) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Created</dt>
                            <dd class="mt-0.5">{{ formatDate(viewingNotice.created_at) }}</dd>
                        </div>
                    </dl>
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeView()">Close</Button>
                    <Button type="button" @click="openEdit(viewingNotice!)">Edit Notice</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Notice Modal -->
        <Dialog v-model:open="deleteModalOpen" @update:open="(v: boolean) => !v && closeDeleteNotice()">
            <DialogContent v-if="deletingNotice" :show-close-button="true" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete Notice</DialogTitle>
                    <DialogDescription class="sr-only">
                        Confirm deletion of the selected notice.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">
                        Are you sure you want to delete <strong>{{ deletingNotice.title }}</strong>? This action cannot be undone.
                    </p>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeDeleteNotice">Cancel</Button>
                    <Button type="button" variant="destructive" @click="confirmDeleteNotice">Delete</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

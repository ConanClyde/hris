<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { Download, RotateCcw, Trash2, Eye } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';

type BackupItem = {
    id: number;
    filename: string;
    created_at: string;
    notes?: string;
    size?: string;
};

type PaginatedData = {
    data: BackupItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

defineProps<{
    backups: PaginatedData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Backup' },
];

function formatDate(value: string) {
    try {
        return new Date(value).toLocaleString();
    } catch {
        return value;
    }
}

const runBackupModalOpen = ref(false);
function openRunBackupModal() {
    runBackupModalOpen.value = true;
}
function closeRunBackupModal() {
    runBackupModalOpen.value = false;
}
function confirmRunBackup() {
    router.post(admin.backup.run.url(), {}, { onSuccess: () => closeRunBackupModal() });
}

const restoreModalOpen = ref(false);
const restoringBackup = ref<BackupItem | null>(null);
function openRestoreModal(backup: BackupItem) {
    restoringBackup.value = backup;
    restoreModalOpen.value = true;
}
function closeRestoreModal() {
    restoreModalOpen.value = false;
    restoringBackup.value = null;
}
function confirmRestore() {
    if (!restoringBackup.value) return;
    router.post(admin.backup.restore.url(restoringBackup.value.id), {}, { onSuccess: () => closeRestoreModal() });
}

const deleteModalOpen = ref(false);
const deletingBackup = ref<BackupItem | null>(null);

const detailModalOpen = ref(false);
const detailBackup = ref<BackupItem | null>(null);
function openDetailModal(backup: BackupItem) {
    detailBackup.value = backup;
    detailModalOpen.value = true;
}
function closeDetailModal() {
    detailModalOpen.value = false;
    detailBackup.value = null;
}

function openDeleteBackup(backup: BackupItem) {
    deletingBackup.value = backup;
    deleteModalOpen.value = true;
}
function closeDeleteBackup() {
    deleteModalOpen.value = false;
    deletingBackup.value = null;
}
function confirmDeleteBackup() {
    if (!deletingBackup.value) return;
    router.delete(admin.backup.destroy.url(deletingBackup.value.id), { onSuccess: () => closeDeleteBackup() });
}

const fileInputRef = ref<HTMLInputElement | null>(null);
</script>

<template>
    <Head title="Backup" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Backup
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage system backups and restore points.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Button type="button" @click="openRunBackupModal">Run Backup</Button>
                    <Form
                        :action="admin.backup.upload.url()"
                        method="post"
                        enctype="multipart/form-data"
                        class="inline"
                    >
                        <input ref="fileInputRef" type="file" name="backup_file" accept=".sql,.zip,.sqlite" class="hidden" @change="(e: Event) => (e.target as HTMLInputElement).form?.submit()" />
                        <Button type="button" variant="outline" @click="fileInputRef?.click()">
                            Upload
                        </Button>
                    </Form>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Filename</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Created</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Notes</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="backup in backups.data"
                                :key="backup.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ backup.filename }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(backup.created_at) }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ backup.notes ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="View Details"
                                            @click="openDetailModal(backup)"
                                        >
                                            <Eye class="size-4" />
                                        </Button>
                                        <a
                                            :href="admin.backup.download.url(backup.id)"
                                            class="inline-flex size-9 items-center justify-center rounded-md text-muted-foreground hover:text-primary"
                                            title="Download"
                                        >
                                            <Download class="size-4" />
                                        </a>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="Restore"
                                            @click="openRestoreModal(backup)"
                                        >
                                            <RotateCcw class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="Delete"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                            @click="openDeleteBackup(backup)"
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
                    v-if="!backups.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No backups found.
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="backups.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in backups.links" :key="i">
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
                            ? 'border-brand bg-brand text-white dark:border-brand-light dark:bg-brand-light dark:text-gray-900'
                            : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>

        <!-- Run Backup Modal -->
        <Dialog v-model:open="runBackupModalOpen" @update:open="(v: boolean) => !v && closeRunBackupModal()">
            <DialogContent :show-close-button="true" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Run Backup</DialogTitle>
                    <DialogDescription class="sr-only">
                        Confirm creating a new system backup.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">
                        Create a new backup? This may take a moment.
                    </p>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeRunBackupModal">Cancel</Button>
                    <Button type="button" @click="confirmRunBackup">Run Backup</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Restore Backup Modal -->
        <Dialog v-model:open="restoreModalOpen" @update:open="(v: boolean) => !v && closeRestoreModal()">
            <DialogContent v-if="restoringBackup" :show-close-button="true" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Restore from Backup</DialogTitle>
                    <DialogDescription class="sr-only">
                        Confirm restoring system from a backup file.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">
                        Restore from <strong>{{ restoringBackup.filename }}</strong>? This will replace current data. This action cannot be undone.
                    </p>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeRestoreModal">Cancel</Button>
                    <Button type="button" variant="destructive" @click="confirmRestore">Restore</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Backup Modal -->
        <Dialog v-model:open="deleteModalOpen" @update:open="(v: boolean) => !v && closeDeleteBackup()">
            <DialogContent v-if="deletingBackup" :show-close-button="true" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete Backup</DialogTitle>
                    <DialogDescription class="sr-only">
                        Confirm deletion of a backup file.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">
                        Are you sure you want to delete <strong>{{ deletingBackup.filename }}</strong>? This action cannot be undone.
                    </p>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeDeleteBackup">Cancel</Button>
                    <Button type="button" variant="destructive" @click="confirmDeleteBackup">Delete</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Backup Detail Modal -->
        <Dialog v-model:open="detailModalOpen" @update:open="(v: boolean) => !v && closeDetailModal()">
            <DialogContent v-if="detailBackup" :show-close-button="true" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Backup Detail</DialogTitle>
                    <DialogDescription class="sr-only">
                        View details of the selected backup file.
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-3 py-2">
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <span class="text-muted-foreground">ID:</span>
                        <span class="col-span-2">{{ detailBackup.id }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <span class="text-muted-foreground">Path:</span>
                        <span class="col-span-2 truncate">{{ detailBackup.filename }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <span class="text-muted-foreground">Disk:</span>
                        <span class="col-span-2">local</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <span class="text-muted-foreground">Size:</span>
                        <span class="col-span-2">{{ detailBackup.size ?? 'Unknown' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <span class="text-muted-foreground">Created at:</span>
                        <span class="col-span-2">{{ formatDate(detailBackup.created_at) }}</span>
                    </div>
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeDetailModal">Close</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { Eye, Pencil, Trash2, UserCheck, UserX } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import TableUserCell from '@/components/TableUserCell.vue';
import admin from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';

type UserItem = {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
    first_name?: string;
    middle_name?: string;
    last_name?: string;
    created_at: string;
};

type PaginatedData = {
    data: UserItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        users: PaginatedData;
        filters?: { search?: string; role?: string; status?: string };
        pendingCount?: number;
    }>(),
    { filters: () => ({}), pendingCount: 0 }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Manage Users' },
];

const searchInput = ref(props.filters?.search ?? '');
const filterRole = ref(props.filters?.role || 'all');
const filterStatus = ref(props.filters?.status || 'all');

const addModalOpen = ref(false);
const editModalOpen = ref(false);
const viewModalOpen = ref(false);
const deleteModalOpen = ref(false);
const editingUser = ref<UserItem | null>(null);
const viewingUser = ref<UserItem | null>(null);
const deletingUser = ref<UserItem | null>(null);
const selectedUserIds = ref<number[]>([]);

const editName = ref('');
const editFirstName = ref('');
const editMiddleName = ref('');
const editLastName = ref('');
const editEmail = ref('');
const editRole = ref('employee');
const editIsActive = ref(true);

const addFirstName = ref('');
const addMiddleName = ref('');
const addLastName = ref('');
const addEmail = ref('');
const addPassword = ref('');
const addRole = ref('employee');

const addNameComputed = computed(() =>
    [addFirstName.value, addLastName.value].filter(Boolean).join(' ')
);

watch(editingUser, (u) => {
    if (u) {
        editName.value = u.name || '';
        editFirstName.value = u.first_name || '';
        editMiddleName.value = u.middle_name || '';
        editLastName.value = u.last_name || '';
        editEmail.value = u.email || '';
        editRole.value = u.role || 'employee';
        editIsActive.value = u.is_active ?? true;
    }
}, { immediate: true });

const allSelected = computed(() => props.users.data.length > 0 && selectedUserIds.value.length === props.users.data.length);
function toggleSelectAll() {
    if (allSelected.value) {
        selectedUserIds.value = [];
    } else {
        selectedUserIds.value = props.users.data.map((u) => u.id);
    }
}
function toggleSelect(id: number) {
    const idx = selectedUserIds.value.indexOf(id);
    if (idx >= 0) selectedUserIds.value = selectedUserIds.value.filter((i) => i !== id);
    else selectedUserIds.value = [...selectedUserIds.value, id];
}

function openEdit(u: UserItem) {
    editingUser.value = u;
    editModalOpen.value = true;
    viewModalOpen.value = false;
}
function closeEdit() {
    editModalOpen.value = false;
    editingUser.value = null;
}

function openView(u: UserItem) {
    viewingUser.value = u;
    viewModalOpen.value = true;
}
function closeView() {
    viewModalOpen.value = false;
    viewingUser.value = null;
}

function openDelete(u: UserItem) {
    deletingUser.value = u;
    deleteModalOpen.value = true;
}
function closeDelete() {
    deleteModalOpen.value = false;
    deletingUser.value = null;
}

function resetAddForm() {
    addFirstName.value = '';
    addMiddleName.value = '';
    addLastName.value = '';
    addEmail.value = '';
    addPassword.value = '';
    addRole.value = 'employee';
}

function runBulkAction(action: string) {
    if (selectedUserIds.value.length === 0) return;
    const form = document.getElementById('bulk-action-form') as HTMLFormElement;
    if (form) {
        (document.getElementById('bulk-action-type') as HTMLInputElement).value = action;
        const container = document.getElementById('bulk-action-ids');
        if (container) {
            container.innerHTML = '';
            selectedUserIds.value.forEach((id) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = String(id);
                container.appendChild(input);
            });
        }
        form.submit();
    }
}

function submitAddUser(e: Event) {
    e.preventDefault();
    router.post(admin.users.store.url(), {
        name: addNameComputed.value,
        first_name: addFirstName.value,
        middle_name: addMiddleName.value,
        last_name: addLastName.value,
        email: addEmail.value,
        password: addPassword.value,
        role: addRole.value,
    }, {
        onSuccess: () => { addModalOpen.value = false; resetAddForm(); },
    });
}

function submitEditUser(e: Event) {
    e.preventDefault();
    if (!editingUser.value) return;
    router.put(admin.users.update.url(editingUser.value.id), {
        name: [editFirstName.value, editLastName.value].filter(Boolean).join(' '),
        first_name: editFirstName.value,
        middle_name: editMiddleName.value,
        last_name: editLastName.value,
        email: editEmail.value,
        role: editRole.value,
        is_active: editIsActive.value,
    }, {
        onSuccess: () => closeEdit(),
    });
}

const roleOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'hr', label: 'HR' },
    { value: 'employee', label: 'Employee' },
];

watch(
    () => [props.filters?.search, props.filters?.role, props.filters?.status],
    ([search, role, status]) => {
        searchInput.value = (search as string) ?? '';
        filterRole.value = (role as string) || 'all';
        filterStatus.value = (status as string) || 'all';

    },
    { immediate: true }
);

let debounce: ReturnType<typeof setTimeout> | null = null;
watch([searchInput, filterRole, filterStatus], () => {
    if (debounce) clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterRole.value && filterRole.value !== 'all') query.role = filterRole.value;
        if (filterStatus.value && filterStatus.value !== 'all') query.status = filterStatus.value;

        router.get(admin.users.url(), query, { preserveState: true });
    }, 300);
});

function clearFilters() {
    searchInput.value = '';
    filterRole.value = 'all';
    filterStatus.value = 'all';
    router.get(admin.users.url());

}

function userName(u: UserItem): string {
    return u.name || [u.first_name, u.last_name].filter(Boolean).join(' ') || '—';
}

const currentQuery = computed(() => {
    const q: Record<string, string> = {};
    if (searchInput.value) q.search = searchInput.value;
    if (filterRole.value && filterRole.value !== 'all') q.role = filterRole.value;
    if (filterStatus.value && filterStatus.value !== 'all') q.status = filterStatus.value;
    return q;
});
</script>

<template>
    <Head title="Manage Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Manage Users
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View and manage system users and their roles.
                    </p>
                </div>
                <Button type="button" @click="addModalOpen = true">Add New User</Button>
            </div>

            <!-- Tabs -->
            <nav class="flex gap-1 border-b border-gray-200 dark:border-neutral-700" aria-label="Tabs">
                <Link
                    :href="admin.users.url({ query: { ...currentQuery, status: 'all' } })"
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="(!filterStatus || filterStatus === 'all') ? 'border-[#013CFC] text-[#013CFC] dark:border-[#60C8FC] dark:text-[#60C8FC]' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                >
                    All Users
                </Link>
                <Link
                    :href="admin.users.url({ query: { ...currentQuery, status: 'pending' } })"
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="filterStatus === 'pending' ? 'border-[#013CFC] text-[#013CFC] dark:border-[#60C8FC] dark:text-[#60C8FC]' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                >
                    Pending Approvals
                    <span
                        v-if="(pendingCount ?? 0) > 0"
                        class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400"
                    >
                        {{ pendingCount }}
                    </span>
                </Link>
                <Link
                    :href="admin.users.url({ query: { ...currentQuery, status: 'rejected' } })"
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="filterStatus === 'rejected' ? 'border-[#013CFC] text-[#013CFC] dark:border-[#60C8FC] dark:text-[#60C8FC]' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                >
                    Rejected
                </Link>
            </nav>

            <!-- Filters -->
            <div class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="min-w-[180px] flex-1">
                    <Label for="search" class="sr-only">Search</Label>
                    <Input
                        id="search"
                        v-model="searchInput"
                        type="search"
                        placeholder="Search name, email..."
                        class="h-9"
                    />
                </div>
                <div class="w-[130px]">
                    <Label for="filter-role" class="sr-only">Role</Label>
                    <Select v-model="filterRole">
                        <SelectTrigger id="filter-role" class="h-9">
                            <SelectValue placeholder="Role" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Roles</SelectItem>
                            <SelectItem
                                v-for="opt in roleOptions"
                                :key="opt.value"
                                :value="opt.value"
                            >
                                {{ opt.label }}
                            </SelectItem>
                        </SelectContent>

                    </Select>
                </div>
                <div class="w-[130px]">
                    <Label for="filter-status" class="sr-only">Status</Label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-9">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All</SelectItem>
                            <SelectItem value="active">Active</SelectItem>
                            <SelectItem value="inactive">Inactive</SelectItem>
                            <SelectItem value="pending">Pending</SelectItem>
                            <SelectItem value="rejected">Rejected</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <Button type="button" variant="outline" size="sm" class="h-9" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <!-- Bulk action toolbar -->
                <Form
                    id="bulk-action-form"
                    :action="admin.users.bulk_action.url()"
                    method="post"
                    class="hidden"
                >
                    <input id="bulk-action-type" type="hidden" name="action" value="" />
                    <div id="bulk-action-ids" />
                </Form>
                <div
                    v-if="selectedUserIds.length > 0"
                    class="flex items-center gap-3 border-b border-gray-200 bg-gray-100 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-800"
                >
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        {{ selectedUserIds.length }} selected
                    </span>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button type="button" variant="outline" size="sm">
                                Bulk action
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="start">
                            <DropdownMenuItem @click="runBulkAction('activate')">
                                Activate
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="runBulkAction('deactivate')">
                                Deactivate
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                class="text-red-600 dark:text-red-400"
                                @click="runBulkAction('delete')"
                            >
                                Delete
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <Button type="button" variant="ghost" size="sm" @click="selectedUserIds = []">
                        Clear selection
                    </Button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="w-10 px-4 py-3">
                                    <Checkbox
                                        :checked="allSelected"
                                        :aria-label="allSelected ? 'Deselect all' : 'Select all'"
                                        @update:checked="toggleSelectAll"
                                    />
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Name</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Email</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Role</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Status</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="u in users.data"
                                :key="u.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <Checkbox
                                        :checked="selectedUserIds.includes(u.id)"
                                        :aria-label="`Select ${userName(u)}`"
                                        @update:checked="toggleSelect(u.id)"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="userName(u)"
                                        :subtitle="`#${u.id}`"
                                    />
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">{{ u.email }}</td>
                                <td class="px-4 py-3 capitalize">
                                    <Badge variant="outline">{{ u.role }}</Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="u.is_active ? 'default' : 'secondary'">
                                        {{ u.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <template v-if="filterStatus === 'pending'">
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8 text-muted-foreground hover:text-primary"
                                                title="View"
                                                @click="openView(u)"
                                            >
                                                <Eye class="size-4" />
                                            </Button>
                                        </template>
                                        <template v-else-if="filterStatus === 'rejected'">
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8 text-muted-foreground hover:text-destructive"
                                                title="Delete"
                                                @click="openDelete(u)"
                                            >
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </template>
                                        <template v-else>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8 text-muted-foreground hover:text-primary"
                                                title="View"
                                                @click="openView(u)"
                                            >
                                                <Eye class="size-4" />
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8 text-muted-foreground hover:text-amber-600"
                                                title="Edit"
                                                @click="openEdit(u)"
                                            >
                                                <Pencil class="size-4" />
                                            </Button>
                                            <Form
                                                :action="admin.users.toggleStatus.url(u.id)"
                                                method="post"
                                                class="inline"
                                            >
                                                <input type="hidden" name="_method" value="PATCH" />
                                                <Button
                                                    type="submit"
                                                    variant="ghost"
                                                    size="icon"
                                                    class="h-8 w-8 text-muted-foreground"
                                                    :class="u.is_active ? 'hover:text-destructive' : 'hover:text-primary'"
                                                    :title="u.is_active ? 'Deactivate' : 'Activate'"
                                                >
                                                    <component :is="u.is_active ? UserX : UserCheck" class="size-4" />
                                                </Button>
                                            </Form>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8 text-muted-foreground hover:text-destructive"
                                                title="Delete"
                                                @click="openDelete(u)"
                                            >
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!users.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No users found.
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="users.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in users.links" :key="i">
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

        <!-- Add User modal -->
        <Dialog v-model:open="addModalOpen" @update:open="(v: boolean) => v && resetAddForm()">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Add New User</DialogTitle>
                </DialogHeader>
                <form
                    class="flex flex-col gap-4"
                    @submit.prevent="submitAddUser"
                >
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <Label for="add-first_name">First name</Label>
                                <Input id="add-first_name" v-model="addFirstName" name="first_name" required />
                            </div>
                            <div class="space-y-2">
                                <Label for="add-middle_name">Middle name</Label>
                                <Input id="add-middle_name" v-model="addMiddleName" name="middle_name" />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label for="add-last_name">Last name</Label>
                            <Input id="add-last_name" v-model="addLastName" name="last_name" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="add-email">Email</Label>
                            <Input id="add-email" v-model="addEmail" name="email" type="email" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="add-password">Password</Label>
                            <Input id="add-password" v-model="addPassword" name="password" type="password" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="add-role">Role</Label>
                            <Select v-model="addRole" name="role">
                                <SelectTrigger id="add-role">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="opt in roleOptions"
                                        :key="opt.value"
                                        :value="opt.value"
                                    >
                                        {{ opt.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="addModalOpen = false">
                            Cancel
                        </Button>
                        <Button type="submit">Create User</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit User modal -->
        <Dialog v-model:open="editModalOpen">
            <DialogContent v-if="editingUser" class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Edit User</DialogTitle>
                </DialogHeader>
                <form
                    class="flex flex-col gap-4"
                    @submit.prevent="submitEditUser"
                >
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <Label for="edit-first_name">First name</Label>
                                <Input id="edit-first_name" v-model="editFirstName" name="first_name" />
                            </div>
                            <div class="space-y-2">
                                <Label for="edit-middle_name">Middle name</Label>
                                <Input id="edit-middle_name" v-model="editMiddleName" name="middle_name" />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-last_name">Last name</Label>
                            <Input id="edit-last_name" v-model="editLastName" name="last_name" />
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-email">Email</Label>
                            <Input id="edit-email" v-model="editEmail" name="email" type="email" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-role">Role</Label>
                            <Select v-model="editRole" name="role">
                                <SelectTrigger id="edit-role">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="opt in roleOptions"
                                        :key="opt.value"
                                        :value="opt.value"
                                    >
                                        {{ opt.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex items-center gap-2">
                            <Checkbox id="edit-is_active" v-model:checked="editIsActive" name="is_active" value="1" />
                            <Label for="edit-is_active">Active</Label>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeEdit()">
                            Cancel
                        </Button>
                        <Button type="submit">Save changes</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

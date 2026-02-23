<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { UserCheck, UserX } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type UserItem = {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
    first_name?: string;
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
    }>(),
    { filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Manage Users' },
];

const searchInput = ref(props.filters?.search ?? '');
const filterRole = ref(props.filters?.role || 'all');
const filterStatus = ref(props.filters?.status || 'all');


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
        router.get(hr.users.index.url(), query, { preserveState: true });

    }, 300);
});

function clearFilters() {
    searchInput.value = '';
    filterRole.value = 'all';
    filterStatus.value = 'all';
    router.get(hr.users.index.url());
}

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
                        View and manage user accounts. Approve or toggle status.
                    </p>
                </div>
            </div>

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
                        </SelectContent>
                    </Select>
                </div>
                <Button type="button" variant="outline" size="sm" class="h-9" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
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
                                    <TableUserCell
                                        :name="u.name || [u.first_name, u.last_name].filter(Boolean).join(' ') || '—'"
                                        :subtitle="u.email"
                                    />
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ u.email }}</td>
                                <td class="px-4 py-3">
                                    <Badge variant="outline">{{ u.role }}</Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="u.is_active ? 'default' : 'secondary'">
                                        {{ u.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Form
                                        :action="hr.users.toggleStatus.url(u.id)"
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
                                            <UserX v-if="u.is_active" class="size-4" />
                                            <UserCheck v-else class="size-4" />
                                        </Button>
                                    </Form>
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
                    <button
                        type="button"
                        class="ml-1 text-[#013CFC] hover:underline dark:text-[#60C8FC]"
                        @click="clearFilters"
                    >
                        Clear filters
                    </button>
                </div>
            </div>

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
                        :class="link.active ? 'border-[#013CFC] bg-[#013CFC] text-white dark:border-[#60C8FC] dark:bg-[#60C8FC] dark:text-gray-900' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>
    </AppLayout>
</template>

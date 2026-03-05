<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    CheckCircle2,
    ChevronDown,
    Eye,
    Pencil,
    Trash2,
    XCircle,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import AlertError from '@/components/AlertError.vue';
import Pagination from '@/components/Pagination.vue';
import TableUserCell from '@/components/TableUserCell.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogScrollContent,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useBroadcasting } from '@/composables/useBroadcasting';
import { useOrgUnitSelectors } from '@/composables/useOrgUnitSelectors';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type UserItem = {
    id: number;
    name: string;
    email: string;
    username?: string;
    avatar?: string | null;
    role: string;
    is_active: boolean;
    status: string;
    first_name?: string;
    middle_name?: string;
    last_name?: string;
    name_extension?: string;
    sex?: string;
    date_of_birth?: string;
    position?: string;
    classification?: string;
    date_hired?: string;
    division?: string;
    subdivision?: string;
    section?: string;
    division_id?: number;
    subdivision_id?: number;
    section_id?: number;
    created_at: string;
};

type PaginatedData = {
    data: UserItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        users: PaginatedData;
        filters?: { search?: string; role?: string; status?: string; per_page?: string | number };
        pendingCount?: number;
        rejectedCount?: number;
    }>(),
    { filters: () => ({}), pendingCount: 0, rejectedCount: 0 },
);

const { usersPendingCount } = useBroadcasting();
const { lastUserManagementEvent } = useBroadcasting();
if (usersPendingCount.value === null) {
    usersPendingCount.value = props.pendingCount ?? 0;
}

const pendingCountComputed = computed(
    () => usersPendingCount.value ?? props.pendingCount ?? 0,
);

const realtimeUsers = ref<UserItem[]>([...props.users.data]);
watch(
    () => props.users.data,
    (next) => {
        realtimeUsers.value = [...next];
    },
    { immediate: true },
);

function upsertUserRow(u: Partial<UserItem> & { id: number }) {
    const idx = realtimeUsers.value.findIndex((x) => x.id === u.id);
    if (idx === -1) {
        realtimeUsers.value = [u as UserItem, ...realtimeUsers.value];
        return;
    }
    realtimeUsers.value[idx] = {
        ...realtimeUsers.value[idx],
        ...u,
    } as UserItem;
}

function removeUserRow(id: number) {
    realtimeUsers.value = realtimeUsers.value.filter((x) => x.id !== id);
}

watch(
    () => lastUserManagementEvent.value,
    (evt) => {
        if (!evt) return;

        const user: any = evt.user || {};
        if (typeof user.id !== 'number') return;
        if (user.role === 'admin') return;

        if (evt.type === 'registered') {
            if (filterStatus.value !== 'pending') return;
            if (user.status && user.status !== 'pending') return;
            upsertUserRow(user);
            return;
        }

        if (evt.type === 'identity_updated') {
            upsertUserRow(user);
            if (viewingUser.value && viewingUser.value.id === user.id) {
                viewingUser.value = {
                    ...viewingUser.value,
                    ...user,
                } as any;
            }
            if (editingUser.value && editingUser.value.id === user.id) {
                editingUser.value = {
                    ...editingUser.value,
                    ...user,
                } as any;
            }
            return;
        }

        if (evt.type === 'approved') {
            if (filterStatus.value === 'pending') {
                removeUserRow(user.id);
                return;
            }
            if (filterStatus.value === 'rejected') {
                removeUserRow(user.id);
                return;
            }
            upsertUserRow({ id: user.id, status: 'approved', is_active: true });
            return;
        }

        if (evt.type === 'rejected') {
            if (filterStatus.value === 'pending') {
                removeUserRow(user.id);
                return;
            }
            if (filterStatus.value === 'rejected') {
                upsertUserRow({
                    ...user,
                    status: 'rejected',
                    is_active: false,
                });
                return;
            }
            upsertUserRow({
                id: user.id,
                status: 'rejected',
                is_active: false,
            });
        }
    },
);

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Manage Users' }];

const basePerPage = String(props.filters?.per_page ?? props.users.per_page ?? 8);

// Separate filter states for each tab
const allUsersFilters = ref({
    search: props.filters?.search ?? '',
    role: props.filters?.role || 'all',
    status: props.filters?.status || 'all',
    per_page: basePerPage,
});

const pendingFilters = ref({
    search: props.filters?.search ?? '',
    role: props.filters?.role || 'all',
    per_page: basePerPage,
});

const rejectedFilters = ref({
    search: props.filters?.search ?? '',
    role: props.filters?.role || 'all',
    per_page: basePerPage,
});

// Current active tab
const activeTab = computed(() => {
    if (props.filters?.status === 'pending') return 'pending';
    if (props.filters?.status === 'rejected') return 'rejected';
    return 'all';
});

// Current filter values based on active tab
const searchInput = computed({
    get: () => {
        if (activeTab.value === 'pending') return pendingFilters.value.search;
        if (activeTab.value === 'rejected') return rejectedFilters.value.search;
        return allUsersFilters.value.search;
    },
    set: (value) => {
        if (activeTab.value === 'pending') pendingFilters.value.search = value;
        else if (activeTab.value === 'rejected')
            rejectedFilters.value.search = value;
        else allUsersFilters.value.search = value;
    },
});

const filterRole = computed({
    get: () => {
        if (activeTab.value === 'pending') return pendingFilters.value.role;
        if (activeTab.value === 'rejected') return rejectedFilters.value.role;
        return allUsersFilters.value.role;
    },
    set: (value) => {
        if (activeTab.value === 'pending') pendingFilters.value.role = value;
        else if (activeTab.value === 'rejected')
            rejectedFilters.value.role = value;
        else allUsersFilters.value.role = value;
    },
});

const filterStatus = computed({
    get: () => {
        if (activeTab.value === 'pending') return 'pending';
        if (activeTab.value === 'rejected') return 'rejected';
        return allUsersFilters.value.status;
    },
    set: (value) => {
        if (activeTab.value === 'all') allUsersFilters.value.status = value;
    },
});

const perPage = computed({
    get: () => {
        if (activeTab.value === 'pending') return pendingFilters.value.per_page;
        if (activeTab.value === 'rejected') return rejectedFilters.value.per_page;
        return allUsersFilters.value.per_page;
    },
    set: (value) => {
        if (activeTab.value === 'pending') pendingFilters.value.per_page = value;
        else if (activeTab.value === 'rejected')
            rejectedFilters.value.per_page = value;
        else allUsersFilters.value.per_page = value;
    },
});

const roleOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'hr', label: 'HR' },
    { value: 'employee', label: 'Employee' },
];

watch(
    () => [props.filters?.search, props.filters?.role, props.filters?.status, props.filters?.per_page],
    ([search, role, status, perPageValue]) => {
        const statusValue = (status as string) || 'all';
        const searchValue = (search as string) ?? '';
        const roleValue = (role as string) || 'all';
        const perPageFilter = String(perPageValue ?? basePerPage);

        if (statusValue === 'pending') {
            pendingFilters.value.search = searchValue;
            pendingFilters.value.role = roleValue;
            pendingFilters.value.per_page = perPageFilter;
        } else if (statusValue === 'rejected') {
            rejectedFilters.value.search = searchValue;
            rejectedFilters.value.role = roleValue;
            rejectedFilters.value.per_page = perPageFilter;
        } else {
            allUsersFilters.value.search = searchValue;
            allUsersFilters.value.role = roleValue;
            allUsersFilters.value.status = statusValue;
            allUsersFilters.value.per_page = perPageFilter;
        }
    },
    { immediate: true },
);

let debounce: ReturnType<typeof setTimeout> | null = null;
watch([searchInput, filterRole, filterStatus, perPage], () => {
    if (debounce) clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterRole.value && filterRole.value !== 'all')
            query.role = filterRole.value;
        if (perPage.value) query.per_page = String(perPage.value);
        const statusArg =
            filterStatus.value && filterStatus.value !== 'all'
                ? { status: filterStatus.value }
                : undefined;
        const url = hr.users.index.url(
            statusArg,
            Object.keys(query).length ? { query } : undefined,
        );
        router.get(url, {}, { preserveState: true });
    }, 300);
});

function clearFilters() {
    if (activeTab.value === 'pending') {
        pendingFilters.value.search = '';
        pendingFilters.value.role = 'all';
        pendingFilters.value.per_page = basePerPage;
    } else if (activeTab.value === 'rejected') {
        rejectedFilters.value.search = '';
        rejectedFilters.value.role = 'all';
        rejectedFilters.value.per_page = basePerPage;
    } else {
        allUsersFilters.value.search = '';
        allUsersFilters.value.role = 'all';
        allUsersFilters.value.status = 'all';
        allUsersFilters.value.per_page = basePerPage;
    }

    const statusArg =
        activeTab.value !== 'all' ? { status: activeTab.value } : undefined;
    const query = basePerPage ? { per_page: basePerPage } : undefined;
    router.get(
        hr.users.index.url(
            statusArg,
            query ? { query } : undefined,
        ),
    );
}

// Helper functions for status display
function getStatusLabel(user: any) {
    if (user.status === 'approved') {
        return user.is_active ? 'Active' : 'Inactive';
    }
    if (user.status) {
        return user.status.charAt(0).toUpperCase() + user.status.slice(1);
    }
    return user.is_active ? 'Active' : 'Inactive';
}

function getStatusVariant(user: any) {
    if (user.status === 'pending') {
        return 'secondary'; // Yellow/orange for pending
    } else if (user.status === 'rejected') {
        return 'destructive'; // Red for rejected
    } else if (user.status === 'approved') {
        return 'default'; // Green for approved
    } else {
        // Fallback to is_active based variants
        return user.is_active ? 'default' : 'secondary';
    }
}

const currentQuery = computed(() => {
    const q: Record<string, string> = {};
    if (searchInput.value) q.search = searchInput.value;
    if (filterRole.value && filterRole.value !== 'all')
        q.role = filterRole.value;
    if (perPage.value) q.per_page = String(perPage.value);
    return q;
});

const viewModalOpen = ref(false);
const editModalOpen = ref(false);
const deleteModalOpen = ref(false);
const addModalOpen = ref(false);
const viewingUser = ref<UserItem | null>(null);
const editingUser = ref<UserItem | null>(null);
const deletingUser = ref<UserItem | null>(null);

const editFirstName = ref('');
const editMiddleName = ref('');
const editLastName = ref('');
const editNameExtension = ref('');
const editUsername = ref('');
const editEmail = ref('');
const editIsActive = ref('true');
const editSex = ref('');
const editDateOfBirth = ref('');
const editPosition = ref('');
const editClassification = ref('');
const editDateHired = ref('');
const editDivisionId = ref<number | null>(null);
const editSubdivisionId = ref<number | null>(null);
const editSectionId = ref<number | null>(null);

const addFirstName = ref('');
const addMiddleName = ref('');
const addLastName = ref('');
const addNameExtension = ref('');
const addUsername = ref('');
const addEmail = ref('');
const addPassword = ref(`iloveTRC${new Date().getFullYear()}`);
const addRole = ref('employee');
const addStep = ref<1 | 2 | 3>(1);
const addErrors = ref<Record<string, string>>({});

// Employee-specific fields for Add User
const addSex = ref('');
const addDateOfBirth = ref('');
const addDateHired = ref('');
const addPosition = ref('');
const addClassification = ref('');

const {
    divisions,
    subdivisions,
    sections,
    divisionId: addDivisionId,
    subdivisionId: addSubdivisionId,
    sectionId: addSectionId,
    subdivisionOptions: addSubdivisionOptions,
    sectionOptions: addSectionOptions,
} = useOrgUnitSelectors();

const addNameComputed = computed(() =>
    [addFirstName.value, addLastName.value].filter(Boolean).join(' '),
);

const filteredSubdivisions = computed(() => {
    if (!editDivisionId.value) return [];
    return subdivisions.value.filter(
        (s) => s.division_id === editDivisionId.value,
    );
});

const filteredSections = computed(() => {
    if (editSubdivisionId.value) {
        return sections.value.filter(
            (s) => s.subdivision_id === editSubdivisionId.value,
        );
    }

    if (editDivisionId.value) {
        return sections.value.filter(
            (s) =>
                s.division_id === editDivisionId.value &&
                s.subdivision_id === null,
        );
    }

    return [];
});

watch(
    editingUser,
    (u) => {
        if (u) {
            editFirstName.value = u.first_name ?? '';
            editMiddleName.value = u.middle_name ?? '';
            editLastName.value = u.last_name ?? '';
            editNameExtension.value = u.name_extension ?? '';
            editUsername.value = u.username ?? '';
            editEmail.value = u.email ?? '';
            editIsActive.value =
                u.is_active !== undefined
                    ? u.is_active
                        ? 'true'
                        : 'false'
                    : 'true';
            editSex.value = u.sex ?? '';
            editDateOfBirth.value = u.date_of_birth ?? '';
            editPosition.value = u.position ?? '';
            editClassification.value = u.classification ?? '';
            editDateHired.value = u.date_hired ?? '';
            editDivisionId.value = u.division_id ?? null;
            editSubdivisionId.value = u.subdivision_id ?? null;
            editSectionId.value = u.section_id ?? null;

            if (editDivisionId.value) {
                const subdivisionOk =
                    !editSubdivisionId.value ||
                    filteredSubdivisions.value.some(
                        (s) => s.id === editSubdivisionId.value,
                    );
                if (!subdivisionOk) editSubdivisionId.value = null;
            } else {
                editSubdivisionId.value = null;
            }

            const sectionOk =
                !editSectionId.value ||
                filteredSections.value.some(
                    (s) => s.id === editSectionId.value,
                );
            if (!sectionOk) editSectionId.value = null;
        }
    },
    { immediate: true },
);

watch(editDivisionId, (newDivisionId, oldDivisionId) => {
    if (newDivisionId === oldDivisionId) return;

    if (!newDivisionId) {
        editSubdivisionId.value = null;
        editSectionId.value = null;
        return;
    }

    const subdivisionOk =
        !editSubdivisionId.value ||
        filteredSubdivisions.value.some(
            (s) => s.id === editSubdivisionId.value,
        );
    if (!subdivisionOk) editSubdivisionId.value = null;
    editSectionId.value = null;
});

watch(editSubdivisionId, (newSubdivisionId, oldSubdivisionId) => {
    if (newSubdivisionId === oldSubdivisionId) return;

    const sectionOk =
        !editSectionId.value ||
        filteredSections.value.some((s) => s.id === editSectionId.value);
    if (!sectionOk) editSectionId.value = null;
});

function userName(u: UserItem): string {
    return (
        u.name || [u.first_name, u.last_name].filter(Boolean).join(' ') || '—'
    );
}

function openView(u: UserItem) {
    viewingUser.value = u;
    viewModalOpen.value = true;
}
function closeView() {
    viewModalOpen.value = false;
    viewingUser.value = null;
}

const approvalProcessing = ref<'approve' | 'reject' | null>(null);

function approveViewingUser() {
    if (!viewingUser.value) return;

    approvalProcessing.value = 'approve';
    router.patch(
        hr.users.approve.url(viewingUser.value.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => closeView(),
            onFinish: () => {
                approvalProcessing.value = null;
            },
        },
    );
}

function rejectViewingUser() {
    if (!viewingUser.value) return;

    approvalProcessing.value = 'reject';
    router.patch(
        hr.users.reject.url(viewingUser.value.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => closeView(),
            onFinish: () => {
                approvalProcessing.value = null;
            },
        },
    );
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
    addNameExtension.value = '';
    addUsername.value = '';
    addEmail.value = '';
    addPassword.value = `iloveTRC${new Date().getFullYear()}`;
    addRole.value = 'employee';
    addStep.value = 1;
    addErrors.value = {};
    // Reset employee fields
    addSex.value = '';
    addDateOfBirth.value = '';
    addDateHired.value = '';
    addDivisionId.value = null;
    addSubdivisionId.value = null;
    addSectionId.value = null;
    addPosition.value = '';
    addClassification.value = '';
}

const addErrorsList = computed(() => Object.values(addErrors.value));

const canProceedAddStep2 = computed(() => {
    const baseOk =
        Boolean(addFirstName.value.trim()) && Boolean(addLastName.value.trim());
    if (!baseOk) return false;
    // Employee requires sex and dob
    return Boolean(addSex.value) && Boolean(addDateOfBirth.value);
});

const canProceedAddStep3 = computed(() => {
    const dateHiredOk = Boolean(addDateHired.value);
    if (!dateHiredOk) return false;
    const divisionOk = Boolean(addDivisionId.value);
    if (!divisionOk) return false;
    if (addSubdivisionOptions.value.length && !addSubdivisionId.value)
        return false;
    if (addSectionOptions.value.length && !addSectionId.value) return false;
    const classificationOk = Boolean(addClassification.value);
    if (!classificationOk) return false;
    const positionOk = Boolean(addPosition.value.trim());
    if (!positionOk) return false;
    return true;
});

const canSubmitAdd = computed(() => {
    return (
        Boolean(addUsername.value.trim()) &&
        Boolean(addEmail.value.trim()) &&
        Boolean(addPassword.value)
    );
});

const addLayoutTitle = computed(() => {
    if (addStep.value === 1) return 'Personal Information';
    if (addStep.value === 2) return 'Employment Details';
    return 'Set up login credentials';
});

const addStepLabel = computed(() => `Step ${addStep.value} of 3`);

const addLayoutDescription = computed(() => {
    if (addStep.value === 1) return 'Enter personal details.';
    if (addStep.value === 2)
        return 'Enter employment and organizational details.';
    return 'Set up username, email, and password.';
});

function nextAddStep() {
    if (addStep.value === 1 && canProceedAddStep2.value) addStep.value = 2;
    else if (addStep.value === 2 && canProceedAddStep3.value) addStep.value = 3;
}

function prevAddStep() {
    if (addStep.value === 3) addStep.value = 2;
    else if (addStep.value === 2) addStep.value = 1;
}

function submitAddUser(e: Event) {
    e.preventDefault();
    addErrors.value = {};
    router.post(
        hr.users.store.url(),
        {
            name: addNameComputed.value,
            first_name: addFirstName.value,
            middle_name: addMiddleName.value || null,
            last_name: addLastName.value,
            name_extension: addNameExtension.value || null,
            username: addUsername.value,
            email: addEmail.value,
            password: addPassword.value,
            role: addRole.value,
            sex: addSex.value,
            date_of_birth: addDateOfBirth.value,
            date_hired: addDateHired.value,
            division_id: addDivisionId.value,
            subdivision_id: addSubdivisionId.value,
            section_id: addSectionId.value,
            position: addPosition.value,
            classification: addClassification.value,
        },
        {
            onError: (errors) => {
                addErrors.value = errors;
                if (
                    errors.first_name ||
                    errors.middle_name ||
                    errors.last_name ||
                    errors.name_extension ||
                    errors.sex ||
                    errors.date_of_birth
                ) {
                    addStep.value = 1;
                } else if (
                    errors.date_hired ||
                    errors.division_id ||
                    errors.subdivision_id ||
                    errors.section_id ||
                    errors.position ||
                    errors.classification
                ) {
                    addStep.value = 2;
                } else if (errors.username || errors.email || errors.password) {
                    addStep.value = 3;
                }
            },
            onSuccess: () => {
                addModalOpen.value = false;
                resetAddForm();
            },
        },
    );
}

function submitEditUser(e: Event) {
    e.preventDefault();
    if (!editingUser.value) return;
    router.put(
        hr.users.update.url(editingUser.value.id),
        {
            name: [editFirstName.value, editLastName.value]
                .filter(Boolean)
                .join(' '),
            first_name: editFirstName.value,
            middle_name: editMiddleName.value || null,
            last_name: editLastName.value,
            username: editUsername.value,
            email: editEmail.value,
            is_active: editIsActive.value,
        },
        {
            onSuccess: () => closeEdit(),
        },
    );
}
</script>

<template>
    <Head title="Manage Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Manage Users
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View and manage user accounts. Approve or toggle status.
                    </p>
                </div>
                <Button type="button" @click="addModalOpen = true"
                    >Add Employee</Button
                >
            </div>

            <!-- Tabs -->
            <nav
                class="flex gap-1 border-b border-gray-200 dark:border-neutral-700"
                aria-label="Tabs"
            >
                <Link
                    :href="
                        hr.users.index.url(
                            undefined,
                            Object.keys(currentQuery).length
                                ? { query: currentQuery }
                                : undefined,
                        )
                    "
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                        !filterStatus || filterStatus === 'all'
                            ? 'border-brand text-brand dark:border-brand-light dark:text-brand-light'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    "
                >
                    All Approved
                </Link>
                <Link
                    :href="
                        hr.users.index.url(
                            { status: 'pending' },
                            Object.keys(currentQuery).length
                                ? { query: currentQuery }
                                : undefined,
                        )
                    "
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                        filterStatus === 'pending'
                            ? 'border-brand text-brand dark:border-brand-light dark:text-brand-light'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    "
                >
                    Pending
                    <span
                        v-if="(pendingCountComputed ?? 0) > 0"
                        class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400"
                    >
                        {{ pendingCountComputed }}
                    </span>
                </Link>
                <Link
                    :href="
                        hr.users.index.url(
                            { status: 'rejected' },
                            Object.keys(currentQuery).length
                                ? { query: currentQuery }
                                : undefined,
                        )
                    "
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                        filterStatus === 'rejected'
                            ? 'border-brand text-brand dark:border-brand-light dark:text-brand-light'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    "
                >
                    Rejected
                </Link>
            </nav>

            <!-- Filters -->
            <div
                class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50"
            >
                <div class="min-w-[180px] flex-1">
                    <Label for="search" class="sr-only">Search</Label>
                    <Input
                        id="search"
                        v-model="searchInput"
                        type="search"
                        placeholder="Search name, email..."
                        class="h-10"
                    />
                </div>
                <div class="w-[130px]">
                    <Label for="filter-role" class="sr-only">Role</Label>
                    <Select v-model="filterRole">
                        <SelectTrigger id="filter-role" class="h-10">
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
                <!-- Status filter - only shown for All Users tab -->
                <div v-if="activeTab === 'all'" class="w-[130px]">
                    <Label for="filter-status" class="sr-only">Status</Label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-10">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All</SelectItem>
                            <SelectItem value="active">Active</SelectItem>
                            <SelectItem value="inactive">Inactive</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-[110px]">
                    <Label for="filter-per-page" class="sr-only">Rows</Label>
                    <Select v-model="perPage">
                        <SelectTrigger id="filter-per-page" class="h-10">
                            <SelectValue placeholder="Rows" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="8">8</SelectItem>
                            <SelectItem value="10">10</SelectItem>
                            <SelectItem value="15">15</SelectItem>
                            <SelectItem value="25">25</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <Button type="button" variant="outline" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
            >
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead
                            class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50"
                        >
                            <tr>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Email
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Role
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-neutral-700"
                        >
                            <tr
                                v-for="u in realtimeUsers"
                                :key="u.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="
                                            u.name ||
                                            [u.first_name, u.last_name]
                                                .filter(Boolean)
                                                .join(' ') ||
                                            '—'
                                        "
                                        :avatar="u.avatar"
                                        :subtitle="u.username || u.email"
                                        :user-id="u.id"
                                    />
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ u.email }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge variant="outline">{{
                                        u.role
                                    }}</Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="getStatusVariant(u)">
                                        {{ getStatusLabel(u) }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div
                                        class="flex items-center justify-end gap-1"
                                    >
                                        <template
                                            v-if="filterStatus === 'pending'"
                                        >
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon-sm"
                                                title="View"
                                                @click="openView(u)"
                                            >
                                                <Eye class="size-4" />
                                            </Button>
                                        </template>
                                        <template
                                            v-else-if="
                                                filterStatus === 'rejected'
                                            "
                                        >
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon-sm"
                                                title="Delete"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                                @click="openDelete(u)"
                                            >
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </template>
                                        <template v-else>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon-sm"
                                                title="View"
                                                @click="openView(u)"
                                            >
                                                <Eye class="size-4" />
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon-sm"
                                                title="Edit"
                                                class="hover:text-primary"
                                                @click="openEdit(u)"
                                            >
                                                <Pencil class="size-4" />
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon-sm"
                                                title="Delete"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
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
                    <button
                        type="button"
                        class="ml-1 text-brand hover:underline dark:text-brand-light"
                        @click="clearFilters"
                    >
                        Clear filters
                    </button>
                </div>
            </div>

            <Pagination :meta="users" />
        </div>

        <!-- View User modal -->
        <Dialog v-model:open="viewModalOpen">
            <DialogScrollContent
                v-if="viewingUser"
                class="w-[95vw] max-w-3xl sm:w-[90vw]"
            >
                <DialogHeader>
                    <DialogTitle>View Employee</DialogTitle>
                    <DialogDescription class="sr-only">
                        View employee profile details.
                    </DialogDescription>
                </DialogHeader>
                <div class="max-h-[75vh] space-y-4 overflow-y-auto p-2 sm:p-4">
                    <div class="flex items-center gap-3">
                        <TableUserCell
                            :name="userName(viewingUser)"
                            :avatar="viewingUser.avatar"
                            :subtitle="
                                viewingUser.username || viewingUser.email
                            "
                            :user-id="viewingUser.id"
                        />
                    </div>
                    <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Username
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingUser.username || viewingUser.email }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Name
                            </dt>
                            <dd class="mt-0.5">
                                {{ userName(viewingUser)
                                }}{{
                                    viewingUser.name_extension
                                        ? `, ${viewingUser.name_extension}`
                                        : ''
                                }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Email
                            </dt>
                            <dd class="mt-0.5">{{ viewingUser.email }}</dd>
                        </div>

                        <template v-if="viewingUser.role === 'employee'">
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                >
                                    Sex
                                </dt>
                                <dd class="mt-0.5 capitalize">
                                    {{ viewingUser.sex }}
                                </dd>
                            </div>
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                >
                                    Date of Birth
                                </dt>
                                <dd class="mt-0.5">
                                    {{ viewingUser.date_of_birth }}
                                </dd>
                            </div>
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                >
                                    Position
                                </dt>
                                <dd class="mt-0.5">
                                    {{ viewingUser.position || '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                >
                                    Classification
                                </dt>
                                <dd class="mt-0.5">
                                    {{ viewingUser.classification || '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt
                                    class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                >
                                    Date Hired
                                </dt>
                                <dd class="mt-0.5">
                                    {{ viewingUser.date_hired || '—' }}
                                </dd>
                            </div>

                            <div
                                v-if="
                                    viewingUser.division ||
                                    viewingUser.subdivision ||
                                    viewingUser.section
                                "
                                class="mt-3 space-y-2 border-t pt-3 sm:col-span-2"
                            >
                                <h4
                                    class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                                >
                                    Organizational Unit
                                </h4>
                                <div
                                    class="grid grid-cols-1 gap-2 sm:grid-cols-3"
                                >
                                    <div v-if="viewingUser.division">
                                        <span
                                            class="text-xs font-medium text-muted-foreground"
                                            >Division:</span
                                        >
                                        <div class="mt-0.5">
                                            {{ viewingUser.division }}
                                        </div>
                                    </div>
                                    <div v-if="viewingUser.subdivision">
                                        <span
                                            class="text-xs font-medium text-muted-foreground"
                                            >Subdivision:</span
                                        >
                                        <div class="mt-0.5">
                                            {{ viewingUser.subdivision }}
                                        </div>
                                    </div>
                                    <div v-if="viewingUser.section">
                                        <span
                                            class="text-xs font-medium text-muted-foreground"
                                            >Section:</span
                                        >
                                        <div class="mt-0.5">
                                            {{ viewingUser.section }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Role
                            </dt>
                            <dd class="mt-0.5 capitalize">
                                {{ viewingUser.role }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Status
                            </dt>
                            <dd class="mt-0.5">
                                <Badge :variant="getStatusVariant(viewingUser)">
                                    {{ getStatusLabel(viewingUser) }}
                                </Badge>
                            </dd>
                        </div>
                    </dl>
                </div>
                <DialogFooter>
                    <DropdownMenu v-if="viewingUser.status === 'pending'">
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                type="button"
                                variant="outline"
                                :disabled="approvalProcessing !== null"
                                class="gap-2"
                            >
                                Approve / Reject
                                <ChevronDown class="size-4 opacity-70" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-52">
                            <DropdownMenuItem
                                :disabled="approvalProcessing !== null"
                                @select="approveViewingUser"
                            >
                                <CheckCircle2 class="mr-2 size-4" />
                                Approve
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem
                                variant="destructive"
                                :disabled="approvalProcessing !== null"
                                @select="rejectViewingUser"
                            >
                                <XCircle class="mr-2 size-4" />
                                Reject
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <Button type="button" variant="outline" @click="closeView()"
                        >Close</Button
                    >
                </DialogFooter>
            </DialogScrollContent>
        </Dialog>

        <!-- Edit User modal -->
        <Dialog v-model:open="editModalOpen">
            <DialogScrollContent
                v-if="editingUser"
                class="w-[95vw] max-w-3xl sm:w-[90vw]"
            >
                <DialogHeader>
                    <DialogTitle>Edit Employee</DialogTitle>
                    <DialogDescription class="sr-only">
                        Edit employee profile information.
                    </DialogDescription>
                </DialogHeader>
                <form
                    class="flex flex-col gap-4"
                    @submit.prevent="submitEditUser"
                >
                    <div
                        class="max-h-[75vh] space-y-4 overflow-y-auto p-2 sm:p-4"
                    >
                        <div class="space-y-2">
                            <Label for="edit-username">Username</Label>
                            <Input
                                id="edit-username"
                                v-model="editUsername"
                                disabled
                                class="cursor-not-allowed bg-gray-50/50 text-muted-foreground dark:bg-neutral-800/50"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <Label for="edit-first_name">First name</Label>
                                <Input
                                    id="edit-first_name"
                                    v-model="editFirstName"
                                    required
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="edit-middle_name"
                                    >Middle name</Label
                                >
                                <Input
                                    id="edit-middle_name"
                                    v-model="editMiddleName"
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="edit-last_name">Last name</Label>
                            <Input
                                id="edit-last_name"
                                v-model="editLastName"
                                required
                            />
                        </div>

                        <div class="space-y-2">
                            <Label for="edit-name_extension"
                                >Name Extension</Label
                            >
                            <Input
                                id="edit-name_extension"
                                v-model="editNameExtension"
                                placeholder="e.g. Jr., Sr."
                            />
                        </div>

                        <div class="space-y-2">
                            <Label for="edit-email">Email</Label>
                            <Input
                                id="edit-email"
                                v-model="editEmail"
                                type="email"
                                required
                            />
                        </div>

                        <!-- Employee-specific fields -->
                        <template v-if="editingUser?.role === 'employee'">
                            <div class="mt-2 border-t pt-4">
                                <h4
                                    class="mb-3 text-sm font-medium text-muted-foreground"
                                >
                                    Employee Details
                                </h4>
                                <div
                                    class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                >
                                    <div class="space-y-2">
                                        <Label for="edit-sex">Sex</Label>
                                        <Select v-model="editSex">
                                            <SelectTrigger id="edit-sex">
                                                <SelectValue
                                                    placeholder="Select Sex"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="male"
                                                    >Male</SelectItem
                                                >
                                                <SelectItem value="female"
                                                    >Female</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="edit-date_of_birth"
                                            >Date of Birth</Label
                                        >
                                        <Input
                                            id="edit-date_of_birth"
                                            v-model="editDateOfBirth"
                                            type="date"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="edit-position"
                                            >Position</Label
                                        >
                                        <Input
                                            id="edit-position"
                                            v-model="editPosition"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="edit-classification"
                                            >Classification</Label
                                        >
                                        <Input
                                            id="edit-classification"
                                            v-model="editClassification"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="edit-date_hired"
                                            >Date Hired</Label
                                        >
                                        <Input
                                            id="edit-date_hired"
                                            v-model="editDateHired"
                                            type="date"
                                        />
                                    </div>
                                </div>
                                <div class="mt-6 border-t pt-4">
                                    <h5 class="mb-3 text-sm font-medium">
                                        Organizational Unit
                                    </h5>
                                    <div
                                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                                    >
                                        <div class="space-y-2">
                                            <Label for="edit-division"
                                                >Division</Label
                                            >
                                            <Select v-model="editDivisionId">
                                                <SelectTrigger
                                                    id="edit-division"
                                                >
                                                    <SelectValue
                                                        placeholder="Select Division"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem :value="null"
                                                        >None</SelectItem
                                                    >
                                                    <SelectItem
                                                        v-for="division in divisions"
                                                        :key="division.id"
                                                        :value="division.id"
                                                    >
                                                        {{ division.name }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                        <div
                                            v-if="
                                                editDivisionId &&
                                                filteredSubdivisions.length
                                            "
                                            class="space-y-2"
                                        >
                                            <Label for="edit-subdivision"
                                                >Subdivision</Label
                                            >
                                            <Select v-model="editSubdivisionId">
                                                <SelectTrigger
                                                    id="edit-subdivision"
                                                >
                                                    <SelectValue
                                                        placeholder="Select Subdivision"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem :value="null"
                                                        >None</SelectItem
                                                    >
                                                    <SelectItem
                                                        v-for="subdivision in filteredSubdivisions"
                                                        :key="subdivision.id"
                                                        :value="subdivision.id"
                                                    >
                                                        {{ subdivision.name }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                        <div
                                            v-if="
                                                editDivisionId &&
                                                filteredSections.length
                                            "
                                            class="space-y-2"
                                        >
                                            <Label for="edit-section"
                                                >Section</Label
                                            >
                                            <Select v-model="editSectionId">
                                                <SelectTrigger
                                                    id="edit-section"
                                                >
                                                    <SelectValue
                                                        placeholder="Select Section"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem :value="null"
                                                        >None</SelectItem
                                                    >
                                                    <SelectItem
                                                        v-for="section in filteredSections"
                                                        :key="section.id"
                                                        :value="section.id"
                                                    >
                                                        {{ section.name }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="space-y-2">
                            <Label for="edit-status">Status</Label>
                            <Select v-model="editIsActive">
                                <SelectTrigger id="edit-status">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="true">Active</SelectItem>
                                    <SelectItem value="false"
                                        >Inactive</SelectItem
                                    >
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="closeEdit()"
                            >Cancel</Button
                        >
                        <Button type="submit">Save changes</Button>
                    </DialogFooter>
                </form>
            </DialogScrollContent>
        </Dialog>

        <!-- Delete User modal -->
        <Dialog v-model:open="deleteModalOpen">
            <DialogContent
                v-if="deletingUser"
                :show-close-button="true"
                class="max-w-md"
            >
                <DialogHeader>
                    <DialogTitle>Delete User</DialogTitle>
                    <DialogDescription class="sr-only">
                        Confirm user account deletion.
                    </DialogDescription>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Are you sure you want to delete
                        <strong>{{ userName(deletingUser) }}</strong
                        >? This action cannot be undone.
                    </p>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeDelete()"
                        >Cancel</Button
                    >
                    <Button
                        type="button"
                        variant="destructive"
                        @click="
                            router.delete(
                                hr.users.destroy.url(deletingUser.id),
                                { onSuccess: () => closeDelete() },
                            )
                        "
                    >
                        Delete User
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Add User modal -->
        <Dialog
            v-model:open="addModalOpen"
            @update:open="(v: boolean) => v && resetAddForm()"
        >
            <DialogScrollContent
                class="max-h-[90vh] w-[98vw] sm:w-[95vw] md:max-w-2xl lg:max-w-4xl"
            >
                <DialogHeader class="sr-only">
                    <DialogTitle>Add Employee</DialogTitle>
                    <DialogDescription>
                        Create a new employee account.
                    </DialogDescription>
                </DialogHeader>
                <form
                    class="flex h-full min-h-[50vh] flex-1 flex-col justify-center p-2 sm:min-h-[600px] sm:p-4"
                    @submit.prevent="submitAddUser"
                >
                    <div
                        class="mx-auto my-auto flex w-full min-w-0 flex-col justify-center sm:max-w-2xl"
                    >
                        <h1
                            class="mb-2 text-center text-xl font-semibold text-gray-900 sm:text-2xl dark:text-gray-100"
                        >
                            {{ addLayoutTitle }}
                        </h1>
                        <p
                            class="mb-2 text-center text-base font-medium text-gray-500 dark:text-gray-400"
                        >
                            {{ addStepLabel }}
                        </p>
                        <div class="mb-4 flex gap-1">
                            <div
                                v-for="i in 3"
                                :key="i"
                                class="h-1.5 flex-1 rounded-sm transition-colors"
                                :class="
                                    i <= addStep
                                        ? 'bg-gray-900 dark:bg-gray-100'
                                        : 'bg-gray-200 dark:bg-neutral-700'
                                "
                            />
                        </div>
                        <p
                            class="mb-6 text-center text-base text-gray-600 dark:text-gray-400"
                        >
                            {{ addLayoutDescription }}
                        </p>

                        <AlertError
                            v-if="addErrorsList.length"
                            :errors="addErrorsList"
                            class="mb-4 border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20"
                        />

                        <div class="space-y-6 p-1">
                            <!-- Step 1: Personal Information -->
                            <div
                                v-show="addStep === 1"
                                class="flex min-w-0 flex-col gap-6"
                            >
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="grid gap-2">
                                        <Label for="add-first_name"
                                            >First name</Label
                                        >
                                        <Input
                                            id="add-first_name"
                                            v-model="addFirstName"
                                            placeholder="Juan"
                                            required
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-middle_name"
                                            >Middle name</Label
                                        >
                                        <Input
                                            id="add-middle_name"
                                            v-model="addMiddleName"
                                            placeholder="Andrade"
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-last_name"
                                            >Last name</Label
                                        >
                                        <Input
                                            id="add-last_name"
                                            v-model="addLastName"
                                            placeholder="Dela Cruz"
                                            required
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-name_extension"
                                            >Name extension</Label
                                        >
                                        <Input
                                            id="add-name_extension"
                                            v-model="addNameExtension"
                                            placeholder="Jr., Sr., III"
                                        />
                                    </div>
                                </div>
                                <!-- Employee fields: sex and dob -->
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="grid gap-2">
                                        <Label for="add-sex">Sex</Label>
                                        <Select v-model="addSex">
                                            <SelectTrigger id="add-sex">
                                                <SelectValue
                                                    placeholder="Select sex"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="male"
                                                    >Male</SelectItem
                                                >
                                                <SelectItem value="female"
                                                    >Female</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div class="grid min-w-0 gap-2">
                                        <Label for="add-dob"
                                            >Date of birth</Label
                                        >
                                        <Input
                                            id="add-dob"
                                            v-model="addDateOfBirth"
                                            type="date"
                                            class="w-full min-w-0"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Employment Details -->
                            <div
                                v-show="addStep === 2"
                                class="flex min-w-0 flex-col gap-6"
                            >
                                <div
                                    class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1"
                                >
                                    <div class="grid gap-2">
                                        <Label for="add-date_hired"
                                            >Date hired</Label
                                        >
                                        <Input
                                            id="add-date_hired"
                                            v-model="addDateHired"
                                            type="date"
                                            class="w-full"
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-division"
                                            >Division</Label
                                        >
                                        <Select v-model="addDivisionId">
                                            <SelectTrigger id="add-division">
                                                <SelectValue
                                                    placeholder="Select division"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="d in divisions"
                                                    :key="d.id"
                                                    :value="d.id"
                                                >
                                                    {{ d.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div
                                        v-if="
                                            addDivisionId &&
                                            addSubdivisionOptions.length
                                        "
                                        class="grid gap-2"
                                    >
                                        <Label for="add-subdivision"
                                            >Subdivision</Label
                                        >
                                        <Select v-model="addSubdivisionId">
                                            <SelectTrigger id="add-subdivision">
                                                <SelectValue
                                                    placeholder="Select subdivision"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="s in addSubdivisionOptions"
                                                    :key="s.id"
                                                    :value="s.id"
                                                >
                                                    {{ s.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div
                                        v-if="
                                            addDivisionId &&
                                            addSectionOptions.length
                                        "
                                        class="grid gap-2"
                                    >
                                        <Label for="add-section">Section</Label>
                                        <Select v-model="addSectionId">
                                            <SelectTrigger id="add-section">
                                                <SelectValue
                                                    placeholder="Select section"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="s in addSectionOptions"
                                                    :key="s.id"
                                                    :value="s.id"
                                                >
                                                    {{ s.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-position"
                                            >Position</Label
                                        >
                                        <Input
                                            id="add-position"
                                            v-model="addPosition"
                                            placeholder="e.g. Administrative Assistant"
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-classification"
                                            >Classification</Label
                                        >
                                        <Select v-model="addClassification">
                                            <SelectTrigger
                                                id="add-classification"
                                            >
                                                <SelectValue
                                                    placeholder="Select classification"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="Regular"
                                                    >Regular</SelectItem
                                                >
                                                <SelectItem value="Detailed"
                                                    >Detailed</SelectItem
                                                >
                                                <SelectItem value="COS"
                                                    >COS</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Credentials -->
                            <div
                                v-show="addStep === 3"
                                class="flex min-w-0 flex-col gap-6"
                            >
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="grid gap-2">
                                        <Label for="add-username"
                                            >Username</Label
                                        >
                                        <Input
                                            id="add-username"
                                            v-model="addUsername"
                                            placeholder="juandelacruz"
                                            required
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-email">Email</Label>
                                        <Input
                                            id="add-email"
                                            v-model="addEmail"
                                            type="email"
                                            placeholder="name@example.com"
                                            required
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label for="add-password"
                                            >Password</Label
                                        >
                                        <Input
                                            id="add-password"
                                            v-model="addPassword"
                                            name="password"
                                            autocomplete="new-password"
                                            disabled
                                            class="cursor-not-allowed bg-gray-50/50 text-muted-foreground dark:bg-neutral-800/50"
                                        />
                                    </div>
                                    <div class="grid gap-2">
                                        <Label>Role</Label>
                                        <Input
                                            :model-value="'Employee'"
                                            disabled
                                            class="cursor-not-allowed bg-gray-50/50 text-muted-foreground dark:bg-neutral-800/50"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto flex justify-between gap-4 pt-8">
                            <Button
                                type="button"
                                variant="outline"
                                @click="
                                    addStep === 1
                                        ? (addModalOpen = false)
                                        : prevAddStep()
                                "
                            >
                                {{ addStep === 1 ? 'Cancel' : 'Back' }}
                            </Button>
                            <Button
                                v-if="addStep === 1"
                                type="button"
                                @click="nextAddStep"
                                :disabled="!canProceedAddStep2"
                            >
                                Next
                            </Button>
                            <Button
                                v-else-if="addStep === 2"
                                type="button"
                                @click="nextAddStep"
                                :disabled="!canProceedAddStep3"
                            >
                                Next
                            </Button>
                            <Button
                                v-else
                                type="submit"
                                :disabled="!canSubmitAdd"
                            >
                                Create account
                            </Button>
                        </div>
                    </div>
                </form>
            </DialogScrollContent>
        </Dialog>
    </AppLayout>
</template>

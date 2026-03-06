<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Building2,
    ChevronDown,
    FolderTree,
    Network,
    Search,
    Users,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
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
import type { BreadcrumbItem } from '@/types';

// Types for organizational structure
interface Section {
    id: number;
    name: string;
}

interface Subdivision {
    id: number;
    name: string;
    sections: Section[];
}

// Employee interface for section modal
interface Employee {
    id: number;
    employee_id: string;
    first_name: string;
    last_name: string;
    division_id: number;
    subdivision_id?: number;
    section_id?: number;
    avatar?: string | null;
}

interface Division {
    id: number;
    name: string;
    subdivisions: Subdivision[];
    sections: Section[];
}

interface EmployeeCount {
    division_id: number;
    subdivision_id?: number;
    section_id?: number;
    count: number;
}

const props = defineProps<{
    structure: Division[];
    employeeCounts: EmployeeCount[];
    employees: Employee[];
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Organizational Chart' }];

// View mode: 'tree' | 'hierarchy' | 'visual'
const viewMode = ref<'tree' | 'hierarchy' | 'visual'>('visual');

// Search functionality
const searchQuery = ref('');

// Expanded state tracking
const expandedDivisions = ref<Set<number>>(new Set());
const expandedSubdivisions = ref<Set<number>>(new Set());

// Modal state for section employees
const sectionModalOpen = ref(false);
const selectedSection = ref<{
    id: number;
    name: string;
    divisionId: number;
    subdivisionId?: number;
} | null>(null);

// Get employees for selected section
const sectionEmployees = computed(() => {
    if (!selectedSection.value) return [];
    const { divisionId, subdivisionId, id: sectionId } = selectedSection.value;
    return props.employees.filter(
        (e) =>
            e.division_id === divisionId &&
            (e.subdivision_id === subdivisionId ||
                (e.subdivision_id === null && subdivisionId === undefined)) &&
            e.section_id === sectionId,
    );
});

// Filter by division
const selectedDivision = ref<string>('all');

// Toggle expansion functions
function toggleDivision(id: number) {
    const newSet = new Set(expandedDivisions.value);
    if (newSet.has(id)) {
        newSet.delete(id);
    } else {
        newSet.add(id);
    }
    expandedDivisions.value = newSet;
}

function toggleSubdivision(id: number) {
    const newSet = new Set(expandedSubdivisions.value);
    if (newSet.has(id)) {
        newSet.delete(id);
    } else {
        newSet.add(id);
    }
    expandedSubdivisions.value = newSet;
}

function expandAll() {
    const allDivisions = props.structure.map((d) => d.id);
    const allSubdivisions = props.structure.flatMap((d) =>
        d.subdivisions.map((s) => s.id),
    );
    expandedDivisions.value = new Set(allDivisions);
    expandedSubdivisions.value = new Set(allSubdivisions);
}

// Open section employee modal
function openSectionModal(
    section: Section,
    divisionId: number,
    subdivisionId?: number,
) {
    selectedSection.value = { ...section, divisionId, subdivisionId };
    sectionModalOpen.value = true;
}

function closeSectionModal(open: boolean) {
    sectionModalOpen.value = open;
    if (!open) {
        selectedSection.value = null;
    }
}

// Format employee name for display
function getEmployeeFullName(employee: Employee): string {
    return `${employee.first_name} ${employee.last_name}`;
}

function getEmployeeInitials(employee: Employee): string {
    const first = employee.first_name?.trim()?.[0] || '';
    const last = employee.last_name?.trim()?.[0] || '';
    return (first + last).toUpperCase();
}

function collapseAll() {
    expandedDivisions.value.clear();
    expandedSubdivisions.value.clear();
}

// Get employee count for a unit
function getEmployeeCount(
    divisionId: number,
    subdivisionId?: number,
    sectionId?: number,
): number {
    const count = props.employeeCounts.find(
        (c) =>
            c.division_id === divisionId &&
            (c.subdivision_id === subdivisionId ||
                (c.subdivision_id === null && subdivisionId === undefined)) &&
            c.section_id === sectionId,
    );
    return count?.count ?? 0;
}

// Get total employees in a division
function getDivisionEmployeeCount(division: Division): number {
    return props.employeeCounts
        .filter((c) => c.division_id === division.id)
        .reduce((sum, c) => sum + c.count, 0);
}

// Get total employees in a subdivision
function getSubdivisionEmployeeCount(
    subdivision: Subdivision,
    divisionId: number,
): number {
    return props.employeeCounts
        .filter(
            (c) =>
                c.division_id === divisionId &&
                c.subdivision_id === subdivision.id,
        )
        .reduce((sum, c) => sum + c.count, 0);
}

// Filtered structure based on search and division filter
const filteredStructure = computed(() => {
    let result = [...props.structure];

    // Filter by division
    if (selectedDivision.value !== 'all') {
        result = result.filter((d) => d.id === Number(selectedDivision.value));
    }

    // Filter by search
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter((division) => {
            // Check if division matches
            if (division.name.toLowerCase().includes(query)) return true;

            // Check if any subdivision matches
            return (
                division.subdivisions.some((sub) => {
                    if (sub.name.toLowerCase().includes(query)) return true;
                    return sub.sections.some((sec) =>
                        sec.name.toLowerCase().includes(query),
                    );
                }) ||
                division.sections.some((sec) =>
                    sec.name.toLowerCase().includes(query),
                )
            );
        });
    }

    return result;
});

// Statistics
const totalDivisions = computed(() => props.structure.length);
const totalSubdivisions = computed(() =>
    props.structure.reduce((sum, d) => sum + d.subdivisions.length, 0),
);
const totalSections = computed(() =>
    props.structure.reduce(
        (sum, d) =>
            sum +
            d.subdivisions.reduce(
                (subSum, s) => subSum + s.sections.length,
                0,
            ) +
            d.sections.length,
        0,
    ),
);
const totalEmployees = computed(() =>
    props.employeeCounts.reduce((sum, c) => sum + c.count, 0),
);

// Division options for filter
const divisionOptions = computed(() =>
    props.structure.map((d) => ({ value: String(d.id), label: d.name })),
);

// Check if item matches search (for highlighting)
function matchesSearch(name: string): boolean {
    if (!searchQuery.value.trim()) return false;
    return name.toLowerCase().includes(searchQuery.value.toLowerCase());
}
</script>

<template>
    <Head title="Organizational Chart" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <!-- Header -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Organizational Chart
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View the organizational structure of TRC and TRC-LU
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Button type="button" variant="outline" @click="expandAll">
                        Expand All
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        @click="collapseAll"
                    >
                        Collapse All
                    </Button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="flex items-center gap-2 text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            <Building2 class="h-4 w-4" />
                            Divisions
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ totalDivisions }}
                        </div>
                    </CardContent>
                </Card>

                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="flex items-center gap-2 text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            <FolderTree class="h-4 w-4" />
                            Subdivisions
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ totalSubdivisions }}
                        </div>
                    </CardContent>
                </Card>

                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="flex items-center gap-2 text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            <Network class="h-4 w-4" />
                            Sections
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ totalSections }}
                        </div>
                    </CardContent>
                </Card>

                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="flex items-center gap-2 text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            <Users class="h-4 w-4" />
                            Total Employees
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ totalEmployees }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filters -->
            <div
                class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50"
            >
                <div class="min-w-[200px] flex-1">
                    <Label for="search" class="sr-only">Search</Label>
                    <div class="relative">
                        <Search
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400"
                        />
                        <Input
                            id="search"
                            v-model="searchQuery"
                            type="search"
                            placeholder="Search divisions, subdivisions, sections..."
                            class="h-10 pl-9"
                        />
                    </div>
                </div>
                <div class="w-[200px]">
                    <Label for="division-filter" class="sr-only"
                        >Filter by Division</Label
                    >
                    <Select v-model="selectedDivision">
                        <SelectTrigger id="division-filter" class="h-10">
                            <SelectValue placeholder="All Divisions" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Divisions</SelectItem>
                            <SelectItem
                                v-for="opt in divisionOptions"
                                :key="opt.value"
                                :value="opt.value"
                            >
                                {{ opt.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <!-- View Tabs -->
            <nav
                class="flex gap-1 border-b border-gray-200 dark:border-neutral-700"
                aria-label="View Tabs"
            >
                <button
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                        viewMode === 'tree'
                            ? 'border-brand text-brand dark:border-brand-light dark:text-brand-light'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    "
                    @click="viewMode = 'tree'"
                >
                    <FolderTree class="h-4 w-4" />
                    Tree View
                </button>
                <button
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                        viewMode === 'hierarchy'
                            ? 'border-brand text-brand dark:border-brand-light dark:text-brand-light'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    "
                    @click="viewMode = 'hierarchy'"
                >
                    <Network class="h-4 w-4" />
                    Hierarchy
                </button>
                <button
                    class="inline-flex items-center gap-2 border-b-2 px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                        viewMode === 'visual'
                            ? 'border-brand text-brand dark:border-brand-light dark:text-brand-light'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    "
                    @click="viewMode = 'visual'"
                >
                    <Network class="h-4 w-4" />
                    Visual Flow
                </button>
            </nav>

            <!-- Organizational Chart -->
            <div
                v-if="viewMode !== 'visual'"
                class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
            >
                <div class="p-4">
                    <!-- Empty State -->
                    <div
                        v-if="filteredStructure.length === 0"
                        class="flex flex-col items-center justify-center py-12 text-center"
                    >
                        <Network
                            class="h-12 w-12 text-gray-300 dark:text-neutral-600"
                        />
                        <h3
                            class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100"
                        >
                            No organizational units found
                        </h3>
                        <p
                            class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Try adjusting your search or filter criteria
                        </p>
                    </div>
                    <div v-else-if="viewMode === 'tree'" class="space-y-4">
                        <div
                            v-for="division in filteredStructure"
                            :key="division.id"
                            class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
                        >
                            <!-- Division Header -->
                            <div
                                class="flex cursor-pointer items-center justify-between border-b border-gray-200 bg-gray-50 p-4 transition-colors hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800/50 dark:hover:bg-neutral-800"
                                :class="{
                                    'bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 dark:hover:bg-amber-900/30':
                                        matchesSearch(division.name),
                                }"
                                @click="toggleDivision(division.id)"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded transition-transform duration-200"
                                        :class="{
                                            'rotate-180': expandedDivisions.has(
                                                division.id,
                                            ),
                                        }"
                                    >
                                        <ChevronDown
                                            class="h-4 w-4 text-gray-500"
                                        />
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Building2 class="h-5 w-5 text-brand" />
                                        <span
                                            class="font-semibold text-gray-900 dark:text-gray-100"
                                        >
                                            {{ division.name }}
                                        </span>
                                    </div>
                                </div>
                                <Badge
                                    variant="secondary"
                                    class="flex items-center gap-1"
                                >
                                    <Users class="h-3 w-3" />
                                    {{ getDivisionEmployeeCount(division) }}
                                </Badge>
                            </div>

                            <!-- Division Content with smooth transition -->
                            <div
                                class="overflow-hidden transition-all duration-300 ease-in-out"
                                :class="
                                    expandedDivisions.has(division.id)
                                        ? 'max-h-[5000px] opacity-100'
                                        : 'max-h-0 opacity-0'
                                "
                            >
                                <div class="p-4">
                                    <!-- Direct Sections under Division -->
                                    <div
                                        v-if="division.sections.length > 0"
                                        class="mb-4"
                                    >
                                        <h4
                                            class="mb-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400"
                                        >
                                            Direct Sections
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <div
                                                v-for="section in division.sections"
                                                :key="section.id"
                                                class="flex cursor-pointer items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700"
                                                :class="{
                                                    'border-amber-300 bg-amber-50 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/20 dark:hover:bg-amber-900/30':
                                                        matchesSearch(
                                                            section.name,
                                                        ),
                                                }"
                                                @click="
                                                    openSectionModal(
                                                        section,
                                                        division.id,
                                                    )
                                                "
                                            >
                                                <Network
                                                    class="h-4 w-4 text-gray-400"
                                                />
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300"
                                                >
                                                    {{ section.name }}
                                                </span>
                                                <Badge
                                                    variant="outline"
                                                    class="text-xs"
                                                >
                                                    {{
                                                        getEmployeeCount(
                                                            division.id,
                                                            undefined,
                                                            section.id,
                                                        )
                                                    }}
                                                </Badge>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subdivisions -->
                                    <div
                                        v-if="division.subdivisions.length > 0"
                                        class="space-y-3"
                                    >
                                        <h4
                                            class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400"
                                        >
                                            Subdivisions
                                        </h4>
                                        <div
                                            v-for="subdivision in division.subdivisions"
                                            :key="subdivision.id"
                                            class="overflow-hidden rounded-md border border-gray-200 dark:border-neutral-700"
                                        >
                                            <!-- Subdivision Header -->
                                            <div
                                                class="flex cursor-pointer items-center justify-between bg-gray-50/50 p-3 transition-colors hover:bg-gray-100 dark:bg-neutral-800/30 dark:hover:bg-neutral-800"
                                                :class="{
                                                    'bg-amber-50/50 hover:bg-amber-100 dark:bg-amber-900/10 dark:hover:bg-amber-900/20':
                                                        matchesSearch(
                                                            subdivision.name,
                                                        ),
                                                }"
                                                @click="
                                                    subdivision.sections
                                                        .length > 0
                                                        ? toggleSubdivision(
                                                              subdivision.id,
                                                          )
                                                        : null
                                                "
                                            >
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <div
                                                        v-if="
                                                            subdivision.sections
                                                                .length > 0
                                                        "
                                                        class="flex h-5 w-5 items-center justify-center rounded transition-transform duration-200"
                                                        :class="{
                                                            'rotate-180':
                                                                expandedSubdivisions.has(
                                                                    subdivision.id,
                                                                ),
                                                        }"
                                                    >
                                                        <ChevronDown
                                                            class="h-3.5 w-3.5 text-gray-500"
                                                        />
                                                    </div>
                                                    <span v-else class="w-5" />
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <FolderTree
                                                            class="h-4 w-4 text-gray-500"
                                                        />
                                                        <span
                                                            class="font-medium text-gray-800 dark:text-gray-200"
                                                        >
                                                            {{
                                                                subdivision.name
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <Badge
                                                    variant="outline"
                                                    class="text-xs"
                                                >
                                                    {{
                                                        getSubdivisionEmployeeCount(
                                                            subdivision,
                                                            division.id,
                                                        )
                                                    }}
                                                </Badge>
                                            </div>

                                            <!-- Subdivision Sections -->
                                            <div
                                                class="overflow-hidden transition-all duration-300 ease-in-out"
                                                :class="
                                                    expandedSubdivisions.has(
                                                        subdivision.id,
                                                    ) &&
                                                    subdivision.sections
                                                        .length > 0
                                                        ? 'max-h-[2000px] opacity-100'
                                                        : 'max-h-0 opacity-0'
                                                "
                                            >
                                                <div
                                                    class="border-t border-gray-200 p-3 dark:border-neutral-700"
                                                >
                                                    <div
                                                        class="flex flex-wrap gap-2"
                                                    >
                                                        <div
                                                            v-for="section in subdivision.sections"
                                                            :key="section.id"
                                                            class="flex cursor-pointer items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-1.5 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700"
                                                            :class="{
                                                                'border-amber-300 bg-amber-50 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/20 dark:hover:bg-amber-900/30':
                                                                    matchesSearch(
                                                                        section.name,
                                                                    ),
                                                            }"
                                                            @click="
                                                                openSectionModal(
                                                                    section,
                                                                    division.id,
                                                                    subdivision.id,
                                                                )
                                                            "
                                                        >
                                                            <Network
                                                                class="h-3.5 w-3.5 text-gray-400"
                                                            />
                                                            <span
                                                                class="text-sm text-gray-700 dark:text-gray-300"
                                                            >
                                                                {{
                                                                    section.name
                                                                }}
                                                            </span>
                                                            <span
                                                                class="text-xs text-gray-500"
                                                            >
                                                                ({{
                                                                    getEmployeeCount(
                                                                        division.id,
                                                                        subdivision.id,
                                                                        section.id,
                                                                    )
                                                                }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="viewMode === 'hierarchy'" class="space-y-6">
                        <div
                            v-for="division in filteredStructure"
                            :key="division.id"
                            class="relative"
                        >
                            <!-- Division Level -->
                            <div
                                class="relative z-10 rounded-lg border-2 border-brand bg-white p-4 shadow-sm dark:bg-neutral-800"
                                :class="{
                                    'border-amber-500 bg-amber-50 dark:bg-amber-900/20':
                                        matchesSearch(division.name),
                                }"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <Building2 class="h-6 w-6 text-brand" />
                                        <div>
                                            <h3
                                                class="font-bold text-gray-900 dark:text-gray-100"
                                            >
                                                {{ division.name }}
                                            </h3>
                                            <p class="text-xs text-gray-500">
                                                Division
                                            </p>
                                        </div>
                                    </div>
                                    <Badge class="bg-brand text-white">
                                        <Users class="mr-1 h-3 w-3" />
                                        {{ getDivisionEmployeeCount(division) }}
                                    </Badge>
                                </div>
                            </div>

                            <!-- Connection Line -->
                            <div class="relative mt-2 ml-8">
                                <div
                                    class="absolute top-0 bottom-0 left-0 w-0.5 bg-gray-300 dark:bg-neutral-600"
                                />

                                <!-- Direct Sections -->
                                <div
                                    v-if="division.sections.length > 0"
                                    class="relative mb-4 space-y-2 pl-6"
                                >
                                    <div
                                        v-for="section in division.sections"
                                        :key="section.id"
                                        class="relative"
                                    >
                                        <div
                                            class="absolute top-3 left-[-24px] h-0.5 w-6 bg-gray-300 dark:bg-neutral-600"
                                        />
                                        <div
                                            class="cursor-pointer rounded-md border border-gray-200 bg-gray-50 p-3 transition-colors hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800/50 dark:hover:bg-neutral-700"
                                            :class="{
                                                'border-amber-300 bg-amber-50 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/20 dark:hover:bg-amber-900/30':
                                                    matchesSearch(section.name),
                                            }"
                                            @click="
                                                openSectionModal(
                                                    section,
                                                    division.id,
                                                )
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <Network
                                                        class="h-4 w-4 text-gray-400"
                                                    />
                                                    <span
                                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    >
                                                        {{ section.name }}
                                                    </span>
                                                </div>
                                                <Badge
                                                    variant="outline"
                                                    class="text-xs"
                                                >
                                                    {{
                                                        getEmployeeCount(
                                                            division.id,
                                                            undefined,
                                                            section.id,
                                                        )
                                                    }}
                                                </Badge>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subdivisions -->
                                <div
                                    v-if="division.subdivisions.length > 0"
                                    class="space-y-4"
                                >
                                    <div
                                        v-for="(
                                            subdivision, idx
                                        ) in division.subdivisions"
                                        :key="subdivision.id"
                                        class="relative pl-6"
                                    >
                                        <div
                                            class="absolute top-4 left-0 h-0.5 w-6 bg-gray-300 dark:bg-neutral-600"
                                            :class="{
                                                'bottom-0':
                                                    idx ===
                                                        division.subdivisions
                                                            .length -
                                                            1 &&
                                                    subdivision.sections
                                                        .length === 0,
                                            }"
                                        />

                                        <!-- Subdivision -->
                                        <div
                                            class="rounded-md border border-gray-300 bg-white p-3 dark:border-neutral-600 dark:bg-neutral-800"
                                            :class="{
                                                'border-amber-400 bg-amber-50 dark:border-amber-600 dark:bg-amber-900/20':
                                                    matchesSearch(
                                                        subdivision.name,
                                                    ),
                                            }"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <FolderTree
                                                        class="h-4 w-4 text-gray-500"
                                                    />
                                                    <span
                                                        class="font-medium text-gray-800 dark:text-gray-200"
                                                    >
                                                        {{ subdivision.name }}
                                                    </span>
                                                </div>
                                                <Badge
                                                    variant="secondary"
                                                    class="text-xs"
                                                >
                                                    {{
                                                        getSubdivisionEmployeeCount(
                                                            subdivision,
                                                            division.id,
                                                        )
                                                    }}
                                                </Badge>
                                            </div>
                                        </div>

                                        <!-- Sections under Subdivision -->
                                        <div
                                            v-if="
                                                subdivision.sections.length > 0
                                            "
                                            class="relative mt-2 space-y-2 pl-6"
                                        >
                                            <div
                                                class="absolute top-0 bottom-3 left-0 w-0.5 bg-gray-200 dark:bg-neutral-700"
                                            />
                                            <div
                                                v-for="section in subdivision.sections"
                                                :key="section.id"
                                                class="relative"
                                            >
                                                <div
                                                    class="absolute top-3 left-[-24px] h-0.5 w-6 bg-gray-200 dark:bg-neutral-700"
                                                />
                                                <div
                                                    class="cursor-pointer rounded-md border border-gray-200 bg-gray-50 p-2 transition-colors hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800/50 dark:hover:bg-neutral-700"
                                                    :class="{
                                                        'border-amber-300 bg-amber-50 hover:bg-amber-100 dark:border-amber-700 dark:bg-amber-900/20 dark:hover:bg-amber-900/30':
                                                            matchesSearch(
                                                                section.name,
                                                            ),
                                                    }"
                                                    @click="
                                                        openSectionModal(
                                                            section,
                                                            division.id,
                                                            subdivision.id,
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex items-center justify-between"
                                                    >
                                                        <div
                                                            class="flex items-center gap-2"
                                                        >
                                                            <Network
                                                                class="h-3.5 w-3.5 text-gray-400"
                                                            />
                                                            <span
                                                                class="text-sm text-gray-700 dark:text-gray-300"
                                                            >
                                                                {{
                                                                    section.name
                                                                }}
                                                            </span>
                                                        </div>
                                                        <span
                                                            class="text-xs text-gray-500"
                                                        >
                                                            {{
                                                                getEmployeeCount(
                                                                    division.id,
                                                                    subdivision.id,
                                                                    section.id,
                                                                )
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Flow (Outside the shared container) -->
            <template v-if="viewMode === 'visual'">
                <!-- Empty State for Visual -->
                <div
                    v-if="filteredStructure.length === 0"
                    class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-200 py-12 text-center dark:border-neutral-700"
                >
                    <Network
                        class="h-12 w-12 text-gray-300 dark:text-neutral-600"
                    />
                    <h3
                        class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100"
                    >
                        No organizational units found
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Try adjusting your search or filter criteria
                    </p>
                </div>

                <div
                    v-for="division in filteredStructure"
                    :key="division.id"
                    class="mb-10 overflow-x-auto last:mb-0"
                >
                    <div class="flex min-w-max flex-col items-center p-8">
                        <!-- Division Node -->
                        <div class="relative flex flex-col items-center">
                            <div
                                class="z-10 rounded-xl border-2 border-brand bg-white p-5 text-center shadow-md dark:border-brand-dark dark:bg-neutral-800"
                                :class="{
                                    'ring-4 ring-amber-200 dark:ring-amber-900/40':
                                        matchesSearch(division.name),
                                }"
                            >
                                <Building2
                                    class="mx-auto mb-2 h-8 w-8 text-brand"
                                />
                                <h3
                                    class="w-48 font-bold text-balance text-gray-900 dark:text-gray-100"
                                >
                                    {{ division.name }}
                                </h3>
                                <Badge class="mt-2 text-xs"
                                    >{{
                                        getDivisionEmployeeCount(division)
                                    }}
                                    Members</Badge
                                >
                            </div>

                            <!-- Connector line to Subdivisions -->
                            <div
                                v-if="
                                    division.subdivisions.length > 0 ||
                                    division.sections.length > 0
                                "
                                class="h-8 w-0.5 bg-gray-300 dark:bg-neutral-600"
                            ></div>
                        </div>

                        <!-- Children Container -->
                        <div
                            v-if="
                                division.subdivisions.length > 0 ||
                                division.sections.length > 0
                            "
                            class="relative flex justify-center gap-8 pt-4"
                        >
                            <!-- Horizontal distribution line built with borders -->
                            <div
                                class="absolute top-0 left-1/2 h-4 border-t-2 border-l-2 border-gray-300 dark:border-neutral-600"
                                :style="{
                                    width: `calc(50% - 1rem)`,
                                    borderLeftWidth:
                                        division.subdivisions.length > 1 ||
                                        division.sections.length > 0
                                            ? '2px'
                                            : '0',
                                }"
                            ></div>
                            <div
                                class="absolute top-0 right-1/2 h-4 border-t-2 border-r-2 border-gray-300 dark:border-neutral-600"
                                :style="{
                                    width: `calc(50% - 1rem)`,
                                    borderRightWidth:
                                        division.subdivisions.length > 1 ||
                                        division.sections.length > 0
                                            ? '2px'
                                            : '0',
                                }"
                            ></div>

                            <!-- Direct Sections -->
                            <div
                                v-for="section in division.sections"
                                :key="'sec-' + section.id"
                                class="relative flex flex-col items-center"
                            >
                                <div
                                    class="absolute -top-4 h-4 w-0.5 bg-gray-300 dark:bg-neutral-600"
                                ></div>
                                <div
                                    class="z-10 w-40 cursor-pointer rounded-lg border border-gray-200 bg-gray-50 p-3 text-center transition-transform hover:-translate-y-1 hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800/80"
                                    :class="{
                                        'border-amber-300 bg-amber-50 dark:border-amber-700 dark:bg-amber-900/30':
                                            matchesSearch(section.name),
                                    }"
                                    @click="
                                        openSectionModal(section, division.id)
                                    "
                                >
                                    <Network
                                        class="mx-auto mb-1.5 h-5 w-5 text-gray-400"
                                    />
                                    <p
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                    >
                                        {{ section.name }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{
                                            getEmployeeCount(
                                                division.id,
                                                undefined,
                                                section.id,
                                            )
                                        }}
                                        emp.
                                    </p>
                                </div>
                            </div>

                            <!-- Subdivisions -->
                            <div
                                v-for="subdivision in division.subdivisions"
                                :key="'sub-' + subdivision.id"
                                class="relative flex flex-col items-center"
                            >
                                <div
                                    class="absolute -top-4 h-4 w-0.5 bg-gray-300 dark:bg-neutral-600"
                                ></div>

                                <!-- Subdivision Node -->
                                <div
                                    class="z-10 w-48 rounded-lg border border-gray-300 bg-white p-4 text-center shadow-sm dark:border-neutral-600 dark:bg-neutral-800"
                                    :class="{
                                        'ring-2 ring-amber-200 dark:ring-amber-900/40':
                                            matchesSearch(subdivision.name),
                                    }"
                                >
                                    <FolderTree
                                        class="mx-auto mb-2 h-6 w-6 text-gray-500"
                                    />
                                    <h4
                                        class="font-medium text-gray-800 dark:text-gray-200"
                                    >
                                        {{ subdivision.name }}
                                    </h4>
                                    <Badge
                                        variant="secondary"
                                        class="mt-2 text-[10px]"
                                        >{{
                                            getSubdivisionEmployeeCount(
                                                subdivision,
                                                division.id,
                                            )
                                        }}
                                        Members</Badge
                                    >
                                </div>

                                <!-- Subdivision Sections -->
                                <div
                                    v-if="subdivision.sections.length > 0"
                                    class="flex flex-col items-center"
                                >
                                    <div
                                        class="h-6 w-0.5 bg-gray-200 dark:bg-neutral-700"
                                    ></div>
                                    <div class="flex flex-col gap-3">
                                        <div
                                            v-for="section in subdivision.sections"
                                            :key="section.id"
                                            class="z-10 w-40 cursor-pointer rounded-md border border-gray-200 bg-gray-50 p-2.5 text-center transition-all hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50"
                                            :class="{
                                                'border-amber-300 bg-amber-50 dark:border-amber-700 dark:bg-amber-900/30':
                                                    matchesSearch(section.name),
                                            }"
                                            @click="
                                                openSectionModal(
                                                    section,
                                                    division.id,
                                                    subdivision.id,
                                                )
                                            "
                                        >
                                            <p
                                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ section.name }}
                                            </p>
                                            <p
                                                class="text-[10px] text-gray-500"
                                            >
                                                {{
                                                    getEmployeeCount(
                                                        division.id,
                                                        subdivision.id,
                                                        section.id,
                                                    )
                                                }}
                                                employees
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <Dialog :open="sectionModalOpen" @update:open="closeSectionModal">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2">
                        <Network class="h-5 w-5 text-brand" />
                        {{ selectedSection?.name }}
                    </DialogTitle>
                    <DialogDescription>
                        Employees in this section ({{
                            sectionEmployees.length
                        }})
                    </DialogDescription>
                </DialogHeader>

                <div class="mt-4">
                    <div
                        v-if="sectionEmployees.length === 0"
                        class="py-8 text-center text-gray-500"
                    >
                        No employees found in this section
                    </div>
                    <div v-else class="max-h-[400px] space-y-2 overflow-y-auto">
                        <div
                            v-for="employee in sectionEmployees"
                            :key="employee.id"
                            class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 dark:border-neutral-700"
                        >
                            <Avatar
                                class="h-10 w-10 shrink-0 overflow-hidden rounded-lg"
                            >
                                <AvatarImage
                                    v-if="
                                        typeof employee.avatar === 'string' &&
                                        employee.avatar.trim() !== ''
                                    "
                                    :src="employee.avatar"
                                    :alt="getEmployeeFullName(employee)"
                                />
                                <AvatarFallback
                                    class="rounded-lg bg-foreground font-semibold text-background"
                                >
                                    {{ getEmployeeInitials(employee) }}
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <p
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ getEmployeeFullName(employee) }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    User ID: {{ employee.employee_id }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

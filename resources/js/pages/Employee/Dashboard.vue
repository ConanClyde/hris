<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FileText, BookOpen, Calendar, ClipboardList, Award, Medal, Star, Crown, CheckCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        leaveCount?: number;
        trainingCount?: number;
        pdsStatus?: string | null;
        user?: { first_name?: string } | null;
        badges?: Array<{
            id: number;
            name: string;
            description: string;
            icon: string;
            bg_color: string;
            pivot?: {
                awarded_at: string;
            };
        }>;
        outToday?: Array<{
            id: number;
            employee_name: string;
            department: string;
            leave_type: string;
            avatar: string | null;
        }>;
    }>(),
    { leaveCount: 0, trainingCount: 0, pdsStatus: null, user: null, badges: () => [], outToday: () => [] }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard' }
];

const icons: Record<string, any> = {
    Award,
    Medal,
    Star,
    Crown,
    CheckCircle,
};

function getIcon(name: string) {
    return icons[name] || Award;
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Dashboard
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Welcome back{{ user?.first_name ? `, ${user.first_name}` : '' }}. Here’s an overview of your activity.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <FileText class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Leave Applications</p>
                            <p class="text-2xl font-semibold">{{ leaveCount }}</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.leaveApplications.index.url()">View leave</Link>
                    </Button>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <BookOpen class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Training records</p>
                            <p class="text-2xl font-semibold">{{ trainingCount }}</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.training.index.url()">View training</Link>
                    </Button>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <ClipboardList class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">PDS status</p>
                            <p class="text-lg font-semibold capitalize">{{ pdsStatus || '—' }}</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.pds.index.url()">Open PDS</Link>
                    </Button>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <Calendar class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Calendar</p>
                            <p class="text-lg font-semibold">View events</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.calendar.url()">Open calendar</Link>
                    </Button>
                </div>
            </div>

            <!-- Badges Section -->
            <div class="mt-8 space-y-4">
                <h2 class="text-lg font-medium tracking-tight text-gray-900 dark:text-gray-100">My Badges</h2>
                <div v-if="badges && badges.length > 0" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                    <div v-for="badge in badges" :key="badge.id" :class="['flex flex-col items-center justify-center rounded-xl border p-4 text-center shadow-sm transition-transform hover:scale-105', badge.bg_color || 'bg-white text-gray-900 border-gray-200 dark:bg-neutral-800 dark:text-gray-100 dark:border-neutral-700']">
                        <component :is="getIcon(badge.icon)" class="mb-2 size-8" />
                        <h3 class="text-sm font-semibold">{{ badge.name }}</h3>
                        <p class="mt-1 text-xs opacity-80" :title="badge.description">{{ badge.description }}</p>
                        <p v-if="badge.pivot?.awarded_at" class="mt-3 text-[10px] font-medium opacity-70 uppercase tracking-widest">
                            Awarded: {{ new Date(badge.pivot.awarded_at).toLocaleDateString() }}
                        </p>
                    </div>
                </div>
                <div v-else class="rounded-lg border border-dashed border-gray-300 p-8 text-center dark:border-neutral-700">
                    <Award class="mx-auto size-8 text-gray-400 dark:text-gray-500" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No badges yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Complete your profile or reach milestones to earn badges!</p>
                </div>
            </div>

            <!-- "Who's Out Today" Widget -->
            <div class="mt-8">
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="border-b border-gray-200 bg-gray-50/50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-800/30">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Who's Out Today</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Colleagues currently on approved leave</p>
                    </div>
                    <div class="p-0">
                        <div v-if="outToday && outToday.length > 0" class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <div
                                v-for="person in outToday"
                                :key="person.id"
                                class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/60"
                            >
                                <div class="flex size-10 items-center justify-center overflow-hidden rounded-full bg-primary/10 text-primary">
                                    <img v-if="person.avatar" :src="`/storage/${person.avatar}`" alt="Avatar" class="h-full w-full object-cover">
                                    <span v-else class="text-xs font-semibold uppercase">{{ person.employee_name.substring(0, 2) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">{{ person.employee_name }}</p>
                                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ person.department }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-400 dark:ring-amber-500/20">
                                        {{ person.leave_type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="flex flex-col items-center justify-center py-8 text-center px-4">
                            <div class="flex size-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                <Calendar class="size-6" />
                            </div>
                            <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">Everyone is in the office!</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No approved leaves scheduled for today.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    FileText,
    BookOpen,
    Calendar,
    ClipboardList,
    Award,
    Medal,
    Star,
    Crown,
    CheckCircle,
    Clock,
} from 'lucide-vue-next';
import MetricCard from '@/components/dashboard/MetricCard.vue';
import SectionCard from '@/components/dashboard/SectionCard.vue';
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
    {
        leaveCount: 0,
        trainingCount: 0,
        pdsStatus: null,
        user: null,
        badges: () => [],
        outToday: () => [],
    },
);

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard' }];

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
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    Dashboard
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Welcome back{{
                        user?.first_name ? `, ${user.first_name}` : ''
                    }}. Here’s an overview of your activity.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <MetricCard
                    title="Daily Time Record"
                    value="DTR"
                    :href="employee.attendance.index.url()"
                    link-text="Open DTR →"
                    :icon="Clock"
                />
                <MetricCard
                    title="Leave Applications"
                    :value="leaveCount"
                    :href="employee.leaveApplications.index.url()"
                    link-text="View leave →"
                    :icon="FileText"
                />
                <MetricCard
                    title="Training Records"
                    :value="trainingCount"
                    :href="employee.training.index.url()"
                    link-text="View training →"
                    :icon="BookOpen"
                />
                <MetricCard
                    title="PDS Status"
                    :value="pdsStatus || '—'"
                    :href="employee.pds.index.url()"
                    link-text="Open PDS →"
                    :icon="ClipboardList"
                />
                <MetricCard
                    title="Calendar"
                    value="Events"
                    :href="employee.calendar.url()"
                    link-text="Open calendar →"
                    :icon="Calendar"
                />
            </div>

            <!-- Badges Section -->
            <SectionCard
                title="My Badges"
                subtitle="Milestones and recognition"
                content-class="p-4"
            >
                <div
                    v-if="badges && badges.length > 0"
                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
                >
                    <div
                        v-for="badge in badges"
                        :key="badge.id"
                        :class="[
                            'flex flex-col items-center justify-center rounded-xl border p-4 text-center shadow-sm transition-transform hover:scale-105',
                            badge.bg_color ||
                                'border-gray-200 bg-white text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-100',
                        ]"
                    >
                        <component
                            :is="getIcon(badge.icon)"
                            class="mb-2 size-8"
                        />
                        <h3 class="text-sm font-semibold">{{ badge.name }}</h3>
                        <p
                            class="mt-1 text-xs opacity-80"
                            :title="badge.description"
                        >
                            {{ badge.description }}
                        </p>
                        <p
                            v-if="badge.pivot?.awarded_at"
                            class="mt-3 text-[10px] font-medium tracking-widest uppercase opacity-70"
                        >
                            Awarded:
                            {{
                                new Date(
                                    badge.pivot.awarded_at,
                                ).toLocaleDateString()
                            }}
                        </p>
                    </div>
                </div>
                <div
                    v-else
                    class="rounded-lg border border-dashed border-gray-300 p-8 text-center dark:border-neutral-700"
                >
                    <Award
                        class="mx-auto size-8 text-gray-400 dark:text-gray-500"
                    />
                    <h3
                        class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                    >
                        No badges yet
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Complete your profile or reach milestones to earn
                        badges!
                    </p>
                </div>
            </SectionCard>

            <!-- "Who's Out Today" Widget -->
            <SectionCard
                title="Who's Out Today"
                subtitle="Colleagues currently on approved leave"
                content-class="p-0"
            >
                <div
                    v-if="outToday && outToday.length > 0"
                    class="divide-y divide-gray-200 dark:divide-neutral-700"
                >
                    <div
                        v-for="person in outToday"
                        :key="person.id"
                        class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/60"
                    >
                        <div
                            class="flex size-10 items-center justify-center overflow-hidden rounded-full bg-primary/10 text-primary"
                        >
                            <img
                                v-if="person.avatar"
                                :src="`/storage/${person.avatar}`"
                                alt="Avatar"
                                class="h-full w-full object-cover"
                            />
                            <span
                                v-else
                                class="text-xs font-semibold uppercase"
                                >{{
                                    person.employee_name.substring(0, 2)
                                }}</span
                            >
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="truncate text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ person.employee_name }}
                            </p>
                            <p
                                class="truncate text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{ person.department }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <span
                                class="inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-amber-600/20 ring-inset dark:bg-amber-900/30 dark:text-amber-400 dark:ring-amber-500/20"
                            >
                                {{ person.leave_type }}
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="flex flex-col items-center justify-center px-4 py-8 text-center"
                >
                    <div
                        class="flex size-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400"
                    >
                        <Calendar class="size-6" />
                    </div>
                    <h3
                        class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100"
                    >
                        Everyone is in the office!
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        No approved leaves scheduled for today.
                    </p>
                </div>
            </SectionCard>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    Cpu,
    Database,
    HardDrive,
    RefreshCw,
    Server,
    Settings,
    Zap,
} from 'lucide-vue-next';
import { onBeforeUnmount, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Metrics = {
    app_env?: string;
    app_debug?: boolean;
    app_timezone?: string;

    memory_mb?: number;
    memory_peak_mb: number;
    php_version: string;
    php_sapi?: string;
    php_memory_limit?: string;
    php_max_execution_time?: string;
    php_upload_max_filesize?: string;
    php_post_max_size?: string;
    opcache_enabled?: boolean;
    opcache_hit_rate?: number | null;
    opcache_memory_used_mb?: number | null;
    laravel_version: string;

    db_connection?: string;
    db_driver?: string;
    db_server_version?: string;

    cache_driver?: string;
    queue_driver?: string;
    queue_pending_count?: number | null;
    queue_failed_count?: number | null;
    mail_driver?: string;

    filesystem_disk?: string;
    storage_free_gb?: number | null;
    storage_writable?: boolean;
};

type Diagnostics = {
    db_ping_ms?: number | null;
    cache_ping_ms?: number | null;
    cache_ok?: boolean | null;
    ran_at?: string;
};

defineProps<{
    metrics: Metrics;
    diagnostics?: Diagnostics | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Performance' }];

let refreshInterval: ReturnType<typeof setInterval> | null = null;
onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload();
    }, 10_000);
});
onBeforeUnmount(() => {
    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = null;
});

function runDiagnostics() {
    router.post('/admin/performance/diagnostics', {}, { preserveScroll: true });
}
</script>

<template>
    <Head title="Performance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    Performance
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    System metrics for this request.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Cpu class="size-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Memory
                            </p>
                            <p
                                class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                            >
                                {{ metrics?.memory_mb ?? '—' }} MB
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Peak: {{ metrics?.memory_peak_mb ?? '—' }} MB
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Server class="size-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                PHP
                            </p>
                            <p
                                class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                            >
                                {{ metrics?.php_version ?? '—' }}
                            </p>
                            <p
                                class="font-mono text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{ metrics?.php_sapi ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Zap class="size-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Laravel
                            </p>
                            <p
                                class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                            >
                                {{ metrics?.laravel_version ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 lg:col-span-2 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Diagnostics
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Manual checks (DB / Cache)
                            </p>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-gray-200 dark:hover:bg-neutral-800"
                            @click="runDiagnostics"
                        >
                            <RefreshCw class="size-4" />
                            Run checks
                        </button>
                    </div>

                    <div
                        v-if="diagnostics"
                        class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-4"
                    >
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                DB ping
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ diagnostics?.db_ping_ms ?? '—' }} ms
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Cache ping
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ diagnostics?.cache_ping_ms ?? '—' }} ms
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Cache OK
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{
                                    diagnostics?.cache_ok === null
                                        ? '—'
                                        : diagnostics?.cache_ok
                                          ? 'yes'
                                          : 'no'
                                }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Ran at
                            </p>
                            <p class="mt-0.5 font-mono break-words">
                                {{ diagnostics?.ran_at ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <p
                        v-else
                        class="mt-4 text-sm text-gray-500 dark:text-gray-400"
                    >
                        No diagnostics run yet.
                    </p>
                </div>

                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Settings class="size-5 text-primary" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Runtime
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Environment & core settings
                            </p>
                        </div>
                    </div>
                    <div
                        class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2"
                    >
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                App env
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.app_env ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Debug
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.app_debug ? 'true' : 'false' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Timezone
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.app_timezone ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                OPcache
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{
                                    metrics?.opcache_enabled
                                        ? 'enabled'
                                        : 'disabled'
                                }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Hit rate
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.opcache_hit_rate ?? '—' }}%
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Memory used
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.opcache_memory_used_mb ?? '—' }} MB
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Database class="size-5 text-primary" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Database
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Connection & server version
                            </p>
                        </div>
                    </div>
                    <div
                        class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2"
                    >
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Default connection
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.db_connection ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Driver
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.db_driver ?? '—' }}
                            </p>
                        </div>
                        <div class="sm:col-span-2">
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Server version
                            </p>
                            <p class="mt-0.5 font-mono break-words">
                                {{ metrics?.db_server_version || '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Server class="size-5 text-primary" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                PHP Limits
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Resource constraints
                            </p>
                        </div>
                    </div>
                    <div
                        class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2"
                    >
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                memory_limit
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.php_memory_limit ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                max_execution_time
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.php_max_execution_time ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                upload_max_filesize
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.php_upload_max_filesize ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                post_max_size
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.php_post_max_size ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <HardDrive class="size-5 text-primary" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Storage & Services
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Drivers & disk space
                            </p>
                        </div>
                    </div>
                    <div
                        class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2"
                    >
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Filesystem disk
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.filesystem_disk ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Free storage
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.storage_free_gb ?? '—' }} GB
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Storage writable
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.storage_writable ? 'yes' : 'no' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Cache
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.cache_driver ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Queue
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.queue_driver ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Queue pending
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.queue_pending_count ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Queue failed
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.queue_failed_count ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs tracking-wider text-muted-foreground uppercase"
                            >
                                Mail
                            </p>
                            <p class="mt-0.5 font-mono">
                                {{ metrics?.mail_driver ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

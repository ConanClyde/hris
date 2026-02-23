<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';

defineProps<{ notice?: unknown }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Notice',
    },
];
</script>

<template>
    <Head title="Edit Notice" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Edit Notice
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Update an existing announcement.
                    </p>
                </div>
                <Link href="#">
                    <Button type="button" variant="outline">Cancel</Button>
                </Link>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
                <div class="space-y-4 max-w-2xl">
                    <div>
                        <Label for="title">Title</Label>
                        <Input id="title" type="text" placeholder="Enter notice title" class="mt-1" />
                    </div>
                    <div>
                        <Label for="content">Content</Label>
                        <textarea
                            id="content"
                            rows="6"
                            class="mt-1 flex min-h-[120px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-[#013CFC] focus:outline-none focus:ring-1 focus:ring-[#013CFC] dark:border-neutral-600 dark:bg-neutral-800 dark:placeholder:text-gray-500"
                            placeholder="Enter notice content"
                        />
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <Button type="button" variant="outline">Cancel</Button>
                        <Button type="button">Save Changes</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';
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
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Notices', href: hr.notices.index.url() },
    { title: 'Create Notice' },
];

const typeOptions = [
    { value: 'info', label: 'Info' },
    { value: 'success', label: 'Success' },
    { value: 'warning', label: 'Warning' },
    { value: 'danger', label: 'Danger' },
];

const noticeType = ref('info');
const isActive = ref(true);
</script>

<template>
    <Head title="Create Notice" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Create notice
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Add a new global notice for users.
                    </p>
                </div>
                <Link :href="hr.notices.index().url">
                    <Button variant="outline">Back to list</Button>
                </Link>
            </div>

            <Form
                v-bind="hr.notices.store.form()"
                class="max-w-2xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900"
            >
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input id="title" name="title" type="text" required placeholder="Notice title" />
                </div>
                <div class="grid gap-2">
                    <Label for="message">Message / Body</Label>
                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        required
                        class="flex min-h-[120px] w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-gray-700 dark:bg-neutral-800 dark:placeholder:text-gray-500"
                        placeholder="Notice content"
                    />
                </div>
                <div class="grid gap-2">
                    <Label for="type">Type</Label>
                    <Select v-model="noticeType" name="type">
                        <SelectTrigger id="type">
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
                    <input type="hidden" name="type" :value="noticeType" />
                </div>
                <div class="grid gap-2">
                    <Label for="expires_at">Expires at (optional)</Label>
                    <Input id="expires_at" name="expires_at" type="date" />
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0" />
                    <input
                        id="is_active"
                        name="is_active"
                        type="checkbox"
                        v-model="isActive"
                        value="1"
                        class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand dark:border-gray-600 dark:bg-neutral-800"
                    />
                    <Label for="is_active" class="cursor-pointer">Active</Label>
                </div>
                <div class="flex gap-2">
                    <Button type="submit">Create notice</Button>
                    <Link :href="hr.notices.index().url">
                        <Button type="button" variant="outline">Cancel</Button>
                    </Link>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>

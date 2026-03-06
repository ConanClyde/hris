<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
</script>

<template>
    <div class="w-full max-w-7xl space-y-8">
        <!-- Display & Appearance -->
        <Card>
            <CardHeader>
                <CardTitle>Display & Appearance</CardTitle>
                <CardDescription
                    >Reduce eye strain with a dark interface.</CardDescription
                >
            </CardHeader>
            <CardContent>
                <div
                    class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p class="text-sm leading-none font-medium">Theme</p>
                        <p class="mt-1.5 text-xs text-muted-foreground">
                            Choose your preferred appearance.
                        </p>
                    </div>
                    <AppearanceTabs />
                </div>
            </CardContent>
        </Card>

        <!-- Security -->
        <Card>
            <CardHeader>
                <CardTitle>Security</CardTitle>
                <CardDescription
                    >Devices currently logged into your
                    account.</CardDescription
                >
            </CardHeader>
            <CardContent>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 text-muted-foreground">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
                            />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p
                            class="flex items-center gap-2 text-sm leading-none font-medium"
                        >
                            Current device
                            <span
                                class="inline-flex items-center rounded-full border border-green-200 bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400"
                                >Current session</span
                            >
                        </p>
                        <p class="mt-1.5 text-xs text-muted-foreground">
                            This device. You are currently logged in here.
                        </p>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

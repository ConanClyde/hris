<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const NOTIFICATION_KEYS = {
    email: 'settings_notify_email',
    leave: 'settings_notify_leave',
    training: 'settings_notify_training',
} as const;

const emailNotifications = ref(true);
const leaveRequestUpdates = ref(true);
const trainingReminders = ref(false);

function loadPref(key: string, fallback: boolean): boolean {
    if (typeof localStorage === 'undefined') return fallback;
    const v = localStorage.getItem(key);
    if (v === null) return fallback;
    return v === '1';
}

function savePref(key: string, value: boolean) {
    localStorage?.setItem(key, value ? '1' : '0');
}

onMounted(() => {
    emailNotifications.value = loadPref(NOTIFICATION_KEYS.email, true);
    leaveRequestUpdates.value = loadPref(NOTIFICATION_KEYS.leave, true);
    trainingReminders.value = loadPref(NOTIFICATION_KEYS.training, false);
});

function toggleEmail() {
    emailNotifications.value = !emailNotifications.value;
    savePref(NOTIFICATION_KEYS.email, emailNotifications.value);
}
function toggleLeave() {
    leaveRequestUpdates.value = !leaveRequestUpdates.value;
    savePref(NOTIFICATION_KEYS.leave, leaveRequestUpdates.value);
}
function toggleTraining() {
    trainingReminders.value = !trainingReminders.value;
    savePref(NOTIFICATION_KEYS.training, trainingReminders.value);
}
</script>

<template>
    <div class="w-full max-w-7xl space-y-8">
        <!-- Display & Appearance -->
        <Card>
            <CardHeader>
                <CardTitle>Display & Appearance</CardTitle>
                <CardDescription>Reduce eye strain with a dark interface.</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium leading-none">Theme</p>
                        <p class="text-xs text-muted-foreground mt-1.5">Choose your preferred appearance.</p>
                    </div>
                    <AppearanceTabs />
                </div>
            </CardContent>
        </Card>

        <!-- Notification Preferences -->
        <Card>
            <CardHeader>
                <CardTitle>Notification Preferences</CardTitle>
                <CardDescription>Manage how you receive updates and reminders.</CardDescription>
            </CardHeader>
            <CardContent class="divide-y divide-border">
                <div class="flex items-center justify-between py-4 first:pt-0">
                    <div>
                        <p class="text-sm font-medium leading-none">Email Notifications</p>
                        <p class="text-xs text-muted-foreground mt-1.5">Receive essential updates via email.</p>
                    </div>
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="emailNotifications"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                        :class="emailNotifications ? 'bg-primary' : 'bg-muted'"
                        @click="toggleEmail"
                    >
                        <span
                            class="pointer-events-none inline-block h-5 w-5 shrink-0 transform rounded-full bg-white transition"
                            :class="emailNotifications ? 'translate-x-5' : 'translate-x-0.5'"
                        />
                    </button>
                </div>
                <div class="flex items-center justify-between py-4">
                    <div>
                        <p class="text-sm font-medium leading-none">Leave Request Updates</p>
                        <p class="text-xs text-muted-foreground mt-1.5">Get notified when your leave status changes.</p>
                    </div>
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="leaveRequestUpdates"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                        :class="leaveRequestUpdates ? 'bg-primary' : 'bg-muted'"
                        @click="toggleLeave"
                    >
                        <span
                            class="pointer-events-none inline-block h-5 w-5 shrink-0 transform rounded-full bg-white transition"
                            :class="leaveRequestUpdates ? 'translate-x-5' : 'translate-x-0.5'"
                        />
                    </button>
                </div>
                <div class="flex items-center justify-between py-4">
                    <div>
                        <p class="text-sm font-medium leading-none">Training Reminders</p>
                        <p class="text-xs text-muted-foreground mt-1.5">Allow reminders for upcoming training sessions.</p>
                    </div>
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="trainingReminders"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                        :class="trainingReminders ? 'bg-primary' : 'bg-muted'"
                        @click="toggleTraining"
                    >
                        <span
                            class="pointer-events-none inline-block h-5 w-5 shrink-0 transform rounded-full bg-white transition"
                            :class="trainingReminders ? 'translate-x-5' : 'translate-x-0.5'"
                        />
                    </button>
                </div>
            </CardContent>
        </Card>

        <!-- Security -->
        <Card>
            <CardHeader>
                <CardTitle>Security</CardTitle>
                <CardDescription>Devices currently logged into your account.</CardDescription>
            </CardHeader>
            <CardContent>
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 text-muted-foreground">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium leading-none flex items-center gap-2">
                                Current device
                                <span class="inline-flex items-center rounded-full border border-green-200 bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">Current session</span>
                            </p>
                            <p class="text-xs text-muted-foreground mt-1.5">This device. You are currently logged in here.</p>
                        </div>
                    </div>
            </CardContent>
        </Card>
    </div>
</template>

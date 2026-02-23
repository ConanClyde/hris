<script setup lang="ts">
import { Head, Form, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import PasswordInput from '@/components/auth/PasswordInput.vue';

type UserProp = {
    id: number;
    name: string;
    first_name?: string | null;
    middle_name?: string | null;
    last_name?: string | null;
    name_extension?: string | null;
    email: string;
    role: string;
    is_active: boolean;
    created_at: string | null;
};

const props = defineProps<{
    user: UserProp;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: employee.dashboard().url },
    { title: 'Profile' },
];

function fullName(u: UserProp): string {
    const first = u.first_name ?? '';
    const middle = u.middle_name ? ` ${u.middle_name.charAt(0)}. ` : ' ';
    const last = u.last_name ?? '';
    const ext = u.name_extension ? ` ${u.name_extension}` : '';
    return trim(first + middle + last + ext) || u.name || 'User';
}

function trim(s: string): string {
    return s.replace(/\s+/g, ' ').trim();
}

const roleTitle = 'Employee';

const { getInitials } = useInitials();

const editModalOpen = ref(false);
const passwordModalOpen = ref(false);
const activityModalOpen = ref(false);
const deleteModalOpen = ref(false);

const currentPassword = ref('');
const newPassword = ref('');
const newPasswordConfirmation = ref('');

const editFirstName = ref('');
const editMiddleName = ref('');
const editLastName = ref('');
const editNameExt = ref('');
const editEmail = ref('');

watch(
    () => props.user,
    (u) => {
        if (u) {
            editFirstName.value = u.first_name ?? '';
            editMiddleName.value = u.middle_name ?? '';
            editLastName.value = u.last_name ?? '';
            editNameExt.value = u.name_extension ?? '';
            editEmail.value = u.email ?? '';
        }
    },
    { immediate: true }
);

function memberSince(createdAt: string | null): string {
    if (!createdAt) return '—';
    try {
        const d = new Date(createdAt);
        return d.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    } catch {
        return '—';
    }
}
</script>

<template>
    <Head title="Profile" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-foreground">
                Profile
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Manage your account settings.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_320px]">
                <!-- Left: Info card -->
                <Card>
                    <CardHeader class="flex flex-col gap-6 sm:flex-row sm:items-start sm:space-y-0">
                        <Avatar class="h-20 w-20 shrink-0 overflow-hidden rounded-lg">
                            <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="fullName(user)" />
                            <AvatarFallback class="rounded-lg bg-foreground text-background text-2xl font-bold">
                                {{ getInitials(fullName(user)) }}
                            </AvatarFallback>
                        </Avatar>
                        <div class="min-w-0 flex-1 space-y-1.5">
                            <CardTitle class="text-lg">{{ fullName(user) }}</CardTitle>
                            <p class="text-sm text-muted-foreground">{{ roleTitle }}</p>
                            <Badge v-if="user.is_active" variant="secondary" class="border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                                Active
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="border-t border-border pt-6">
                        <h3 class="text-sm font-semibold mb-4">Account information</h3>
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">User ID</dt>
                                <dd class="mt-1 text-sm">{{ user.id }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Email</dt>
                                <dd class="mt-1 text-sm">{{ user.email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Role</dt>
                                <dd class="mt-1 text-sm">{{ roleTitle }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Member since</dt>
                                <dd class="mt-1 text-sm">{{ memberSince(user.created_at) }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Full name</dt>
                                <dd class="mt-1 text-sm">{{ fullName(user) }}</dd>
                            </div>
                        </dl>
                    </CardContent>
                </Card>

                <!-- Right: Actions -->
                <div class="lg:order-last">
                    <Card>
                        <CardHeader>
                            <CardTitle>Actions</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                        <Button class="w-full" @click="editModalOpen = true">Edit profile</Button>
                        <Button class="w-full" variant="outline" @click="passwordModalOpen = true">Change password</Button>
                        <Button class="w-full" variant="outline" @click="activityModalOpen = true">Activity logs</Button>
                        <Button class="w-full text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20" variant="outline" @click="deleteModalOpen = true">
                            Delete account
                        </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Edit profile modal -->
        <Dialog v-model:open="editModalOpen">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Edit profile</DialogTitle>
                    <p class="text-sm text-muted-foreground mt-0.5">Update your personal details.</p>
                </DialogHeader>
                <form
                    class="flex flex-col gap-4"
                    @submit.prevent="
                        router.put(employee.profile.update.url(), {
                            first_name: editFirstName,
                            middle_name: editMiddleName,
                            last_name: editLastName,
                            name_extension: editNameExt,
                            email: editEmail,
                        }, { onSuccess: () => { editModalOpen = false; } });
                    "
                >
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="edit-first_name">First name</Label>
                                <Input id="edit-first_name" v-model="editFirstName" name="first_name" />
                            </div>
                            <div class="space-y-2">
                                <Label for="edit-middle_name">Middle name</Label>
                                <Input id="edit-middle_name" v-model="editMiddleName" name="middle_name" />
                            </div>
                            <div class="space-y-2">
                                <Label for="edit-last_name">Last name</Label>
                                <Input id="edit-last_name" v-model="editLastName" name="last_name" />
                            </div>
                            <div class="space-y-2">
                                <Label for="edit-name_extension">Name extension</Label>
                                <Input id="edit-name_extension" v-model="editNameExt" name="name_extension" placeholder="Jr., Sr., III" />
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <Label for="edit-email">Email address</Label>
                                <Input id="edit-email" v-model="editEmail" name="email" type="email" required />
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="editModalOpen = false">Cancel</Button>
                        <Button type="submit">Save changes</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Change password modal -->
        <Dialog v-model:open="passwordModalOpen">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Change password</DialogTitle>
                    <p class="text-sm text-muted-foreground mt-0.5">Enter your current password and choose a new one.</p>
                </DialogHeader>
                <form
                    class="flex flex-col gap-4"
                    @submit.prevent="
                        router.post(employee.profile.password.url(), {
                            current_password: currentPassword,
                            password: newPassword,
                            password_confirmation: newPasswordConfirmation,
                        }, { onSuccess: () => { passwordModalOpen = false; currentPassword = ''; newPassword = ''; newPasswordConfirmation = ''; } });
                    "
                >
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="space-y-2">
                            <Label for="current_password">Current password</Label>
                            <PasswordInput id="current_password" v-model="currentPassword" name="current_password" placeholder="Enter current password" autocomplete="current-password" />
                        </div>
                        <div class="space-y-2">
                            <Label for="password">New password</Label>
                            <PasswordInput id="password" v-model="newPassword" name="password" placeholder="Enter new password" autocomplete="new-password" />
                        </div>
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirm new password</Label>
                            <PasswordInput id="password_confirmation" v-model="newPasswordConfirmation" name="password_confirmation" placeholder="Confirm new password" autocomplete="new-password" />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="passwordModalOpen = false">Cancel</Button>
                        <Button type="submit">Change password</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Activity logs modal (placeholder) -->
        <Dialog v-model:open="activityModalOpen">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Activity logs</DialogTitle>
                    <p class="text-sm text-muted-foreground mt-0.5">Your recent activity.</p>
                </DialogHeader>
                <div class="max-h-[60vh] overflow-y-auto p-4">
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3 pb-4 border-b border-border last:border-0 last:pb-0">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-primary" />
                            <div>
                                <p class="font-medium text-foreground">Logged in</p>
                                <p class="text-xs text-muted-foreground mt-0.5">Recent</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <DialogFooter>
                    <Button @click="activityModalOpen = false">Close</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete account modal -->
        <Dialog v-model:open="deleteModalOpen">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete account</DialogTitle>
                    <p class="text-sm text-muted-foreground mt-0.5">This action cannot be undone. All your data will be permanently removed.</p>
                </DialogHeader>
                <Form
                    :action="employee.profile.delete.url()"
                    method="post"
                    class="flex flex-col gap-4"
                    @success="deleteModalOpen = false"
                >
                    <input type="hidden" name="_method" value="DELETE" />
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <p class="text-sm text-muted-foreground">
                            Are you sure you want to delete your account? Enter your password to confirm.
                        </p>
                        <div class="space-y-2">
                            <Label for="delete_password">Enter your password</Label>
                            <PasswordInput id="delete_password" name="password" placeholder="Enter password to confirm" required />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="deleteModalOpen = false">Cancel</Button>
                        <Button type="submit" variant="destructive">Delete account</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

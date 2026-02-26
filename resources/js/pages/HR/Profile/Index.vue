<script setup lang="ts">
import { Head, Form, router } from '@inertiajs/vue3';
import { Upload, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import PasswordInput from '@/components/auth/PasswordInput.vue';
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
    DialogFooter,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type ActivityLogItem = { id: number; action: string; subject_type?: string | null; subject_id?: number | null; created_at: string | null };
type UserProp = {
    id: number;
    name: string;
    username?: string;
    first_name?: string | null;
    middle_name?: string | null;
    last_name?: string | null;
    name_extension?: string | null;
    email: string;
    role: string;
    is_active: boolean;
    created_at: string | null;
    avatar?: string | null;
    activity_logs?: ActivityLogItem[];
};

const props = defineProps<{
    user: UserProp;
}>();

const breadcrumbs: BreadcrumbItem[] = [
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

const roleTitle = 'HR';

const editModalOpen = ref(false);
const passwordModalOpen = ref(false);
const deleteModalOpen = ref(false);

const currentPassword = ref('');
const newPassword = ref('');
const newPasswordConfirmation = ref('');

const editFirstName = ref('');
const editMiddleName = ref('');
const editLastName = ref('');
const editNameExt = ref('');
const editEmail = ref('');
const removeAvatar = ref(false);
const avatarPreviewUrl = ref<string | null>(null);
const avatarBlob = ref<Blob | null>(null);
const cropModalOpen = ref(false);
const cropImageSrc = ref<string | null>(null);
const cropCanvasRef = ref<HTMLCanvasElement | null>(null);
const avatarInputRef = ref<HTMLInputElement | null>(null);

function openCropModal(file: File) {
    cropImageSrc.value = URL.createObjectURL(file);
    cropModalOpen.value = true;
}
function closeCropModal() {
    if (cropImageSrc.value) URL.revokeObjectURL(cropImageSrc.value);
    cropImageSrc.value = null;
    cropModalOpen.value = false;
}
function drawCrop() {
    const canvas = cropCanvasRef.value;
    const src = cropImageSrc.value;
    if (!canvas || !src) return;
    const img = new Image();
    img.onload = () => {
        const size = Math.min(img.width, img.height);
        const x = (img.width - size) / 2;
        const y = (img.height - size) / 2;
        canvas.width = size;
        canvas.height = size;
        const ctx = canvas.getContext('2d');
        if (ctx) ctx.drawImage(img, x, y, size, size, 0, 0, size, size);
    };
    img.src = src;
}
function applyCrop() {
    const canvas = cropCanvasRef.value;
    if (!canvas) return;
    canvas.toBlob((blob) => {
        if (blob) {
            avatarBlob.value = blob;
            avatarPreviewUrl.value = URL.createObjectURL(blob);
            removeAvatar.value = false;
        }
        closeCropModal();
    }, 'image/jpeg', 0.9);
}
function onAvatarFileChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file?.type.startsWith('image/')) openCropModal(file);
    (e.target as HTMLInputElement).value = '';
}
function clearAvatarSelection() {
    if (avatarPreviewUrl.value) URL.revokeObjectURL(avatarPreviewUrl.value);
    avatarPreviewUrl.value = null;
    avatarBlob.value = null;
    removeAvatar.value = true;
}
function submitEditProfile() {
    const formData = new FormData();
    formData.append('first_name', editFirstName.value);
    formData.append('middle_name', editMiddleName.value);
    formData.append('last_name', editLastName.value);
    formData.append('name_extension', editNameExt.value);
    formData.append('email', editEmail.value);
    formData.append('_method', 'PUT');
    if (removeAvatar.value) formData.append('remove_avatar', '1');
    if (avatarBlob.value) formData.append('avatar', avatarBlob.value, 'avatar.jpg');
    router.post(hr.profile.update.url(), formData, {
        forceFormData: true,
        onSuccess: () => {
            editModalOpen.value = false;
            avatarBlob.value = null;
            if (avatarPreviewUrl.value) URL.revokeObjectURL(avatarPreviewUrl.value);
            avatarPreviewUrl.value = null;
            removeAvatar.value = false;
        },
    });
}
watch(cropModalOpen, (open) => { if (open) setTimeout(drawCrop, 50); }, { flush: 'post' });
watch(editModalOpen, (open) => { if (!open) clearAvatarSelection(); });

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

const { getInitials } = useInitials();

function memberSince(createdAt: string | null): string {
    if (!createdAt) return '—';
    try {
        const d = new Date(createdAt);
        return d.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    } catch {
        return '—';
    }
}
function formatLogDate(iso: string | null): string {
    if (!iso) return '';
    try { return new Date(iso).toLocaleString(); } catch { return iso; }
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
                <!-- Left column: Profile + Activity Logs (same width) -->
                <div class="flex flex-col gap-8">
                    <Card>
                    <CardHeader class="flex flex-col gap-6 sm:flex-row sm:items-start sm:space-y-0">
                        <Avatar class="h-20 w-20 shrink-0 overflow-hidden rounded-lg">
                            <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="fullName(user)" />
                            <AvatarFallback class="rounded-lg bg-foreground text-background text-2xl font-bold">
                                {{ getInitials(fullName(user)) }}
                            </AvatarFallback>
                        </Avatar>
                        <div class="min-w-0 flex-1 space-y-0.5">
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
                                <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Username</dt>
                                <dd class="mt-1 text-sm">{{ user.username }}</dd>
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

                    <!-- Activity Logs card (same width as profile card) -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Activity logs</CardTitle>
                            <p class="text-sm text-muted-foreground mt-0.5">Your recent activity.</p>
                        </CardHeader>
                        <CardContent>
                            <ul v-if="user.activity_logs?.length" class="space-y-3 text-sm">
                                <li v-for="log in user.activity_logs" :key="log.id" class="flex items-start gap-3 border-b border-border pb-3 last:border-0 last:pb-0">
                                    <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-primary" />
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-foreground">{{ log.action }}</p>
                                        <p class="text-xs text-muted-foreground mt-0.5">{{ log.subject_type || '' }} {{ log.created_at ? formatLogDate(log.created_at) : '' }}</p>
                                    </div>
                                </li>
                            </ul>
                            <p v-else class="text-sm text-muted-foreground">No recent activity.</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right: Actions -->
                <div>
                    <Card>
                        <CardHeader>
                            <CardTitle>Actions</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                        <Button class="w-full" @click="editModalOpen = true">Edit profile</Button>
                        <Button class="w-full" variant="outline" @click="passwordModalOpen = true">Change password</Button>
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
                    <DialogDescription class="sr-only">
                        Update your personal profile details.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">Update your personal details.</p>
                </DialogHeader>
                <form class="flex flex-col gap-4" @submit.prevent="submitEditProfile">
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="space-y-2">
                            <Label>Profile photo</Label>
                            <div class="flex items-center gap-4">
                                <Avatar class="h-16 w-16 shrink-0 rounded-lg">
                                    <AvatarImage v-if="avatarPreviewUrl || (user.avatar && !removeAvatar)" :src="avatarPreviewUrl || (removeAvatar ? null : user.avatar) || ''" :alt="fullName(user)" />
                                    <AvatarFallback class="rounded-lg bg-muted text-lg">{{ getInitials(fullName(user)) }}</AvatarFallback>
                                </Avatar>
                                <div class="flex flex-col gap-2">
                                    <input ref="avatarInputRef" type="file" accept="image/*" class="hidden" @change="onAvatarFileChange" />
                                    <Button type="button" variant="outline" size="sm" @click="avatarInputRef?.click()"><Upload class="size-4 mr-1.5" />Upload photo</Button>
                                    <Button v-if="user.avatar || avatarPreviewUrl" type="button" variant="ghost" size="sm" class="text-red-600 hover:text-red-700 dark:text-red-400" @click="clearAvatarSelection"><X class="size-4 mr-1.5" />Remove photo</Button>
                                </div>
                            </div>
                            <p class="text-xs text-muted-foreground">Image cropped to 1:1. Max 2MB.</p>
                        </div>
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

        <!-- Crop avatar modal -->
        <Dialog v-model:open="cropModalOpen" @update:open="(v: boolean) => !v && closeCropModal()">
            <DialogContent class="max-w-md" :show-close-button="true">
                <DialogHeader>
                    <DialogTitle>Crop photo</DialogTitle>
                    <DialogDescription class="sr-only">
                        Crop your profile photo to a square.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">Image will be cropped to a square (1:1).</p>
                </DialogHeader>
                <div class="flex justify-center bg-muted/50 rounded-lg p-4 min-h-[200px]">
                    <canvas ref="cropCanvasRef" class="max-w-full max-h-[40vh] rounded-lg border border-border" />
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeCropModal">Cancel</Button>
                    <Button type="button" @click="applyCrop">Apply</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Change password modal -->
        <Dialog v-model:open="passwordModalOpen">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Change password</DialogTitle>
                    <DialogDescription class="sr-only">
                        Change your account password.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">Enter your current password and choose a new one.</p>
                </DialogHeader>
                <form
                    class="flex flex-col gap-4"
                    @submit.prevent="
                        router.post(hr.profile.password.url(), {
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

        <!-- Delete account modal -->
        <Dialog v-model:open="deleteModalOpen">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete account</DialogTitle>
                    <DialogDescription class="sr-only">
                        Permanently delete your account.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground mt-0.5">This action cannot be undone. All your data will be permanently removed.</p>
                </DialogHeader>
                <Form
                    :action="hr.profile.delete.url()"
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

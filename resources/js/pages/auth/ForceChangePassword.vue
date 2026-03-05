<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import PasswordInput from '@/components/auth/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthSimpleLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import { logout } from '@/routes';

const form = useForm({
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/force-change-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}

function handleLogout() {
    router.post(logout().url);
}
</script>

<template>
    <Head title="Change Password" />

    <AuthSimpleLayout
        title="Update your password"
        description="For security, you must choose a new password before continuing."
    >
        <form class="space-y-4" @submit.prevent="submit">
            <div class="space-y-2">
                <Label for="password">New password</Label>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    autofocus
                    autocomplete="new-password"
                    :error="form.errors.password"
                />
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation">Confirm password</Label>
                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                />
                <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <Button type="submit" class="w-full" :disabled="form.processing">
                Save password and continue
            </Button>
            <Button
                type="button"
                variant="outline"
                class="w-full"
                :disabled="form.processing"
                @click="handleLogout"
            >
                Log out
            </Button>
        </form>
    </AuthSimpleLayout>
</template>


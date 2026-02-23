<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    Clock,
    HelpCircle,
    Key,
    Mail,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AlertError from '@/components/AlertError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { email } from '@/routes/password';

const forgotFeatures = [
    { icon: Key, title: 'Secure recovery', description: 'We verify your identity before reset' },
    { icon: Mail, title: 'Email link', description: 'Get a secure reset link in your inbox' },
    { icon: HelpCircle, title: 'Quick support', description: 'Contact us if you need help' },
    { icon: Clock, title: '24/7 access', description: 'Reset anytime that works for you' },
];

defineProps<{
    status?: string;
}>();

const errorsList = (errors: Record<string, string>) => {
    return Object.values(errors);
};
</script>

<template>
    <AuthBase
        title="Forgot password?"
        description="Enter your email and we'll send you a reset link."
        :header-link="{ href: login().url, label: 'Back to Login' }"
        :features="forgotFeatures"
    >
        <Head title="Forgot password" />

        <div
            v-if="status"
            class="mb-4 rounded-md bg-green-50 p-3 text-sm font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
        >
            {{ status }}
        </div>

        <div class="space-y-6">
            <div class="flex justify-center">
                <div
                    class="flex size-12 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 dark:border-neutral-800 dark:bg-neutral-900"
                >
                    <Key class="size-6 text-gray-500 dark:text-gray-400" />
                </div>
            </div>

            <Form v-bind="email.form()" v-slot="{ errors, processing }" class="flex flex-col gap-6">
                <AlertError
                    v-if="Object.keys(errors).length"
                    :errors="errorsList(errors)"
                    class="mb-4 border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20"
                />

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        autofocus
                        placeholder="name@example.com"
                    />
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
                >
                    <Spinner v-if="processing" />
                    Send Reset Link
                </Button>
            </Form>

            <div class="text-center text-sm text-muted-foreground">
                Remembered your password?
                <TextLink
                    :href="login().url"
                    class="font-medium text-[#013CFC] hover:text-[#0031BC]"
                >
                    Sign in
                </TextLink>
            </div>
        </div>
    </AuthBase>
</template>

<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    LifeBuoy,
    Lock,
    ShieldCheck,
    Zap,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AlertError from '@/components/AlertError.vue';
import PasswordInput from '@/components/auth/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { update } from '@/routes/password';

const resetFeatures = [
    { icon: Lock, title: 'Strong password', description: 'Use a unique, hard-to-guess password' },
    { icon: ShieldCheck, title: 'Security tips', description: 'Avoid reusing passwords elsewhere' },
    { icon: Zap, title: 'Instant activation', description: 'Your new password works right away' },
    { icon: LifeBuoy, title: 'Support', description: 'Need help? Reach out anytime' },
];

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);

const errorsList = (errors: Record<string, string>) => {
    return Object.values(errors);
};
</script>

<template>
    <AuthBase
        title="Reset password"
        description="Please enter your new password below"
        :header-link="{ href: login().url, label: 'Back to Login' }"
        :features="resetFeatures"
    >
        <Head title="Reset password" />

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-6">
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
                        v-model="inputEmail"
                        class="mt-1 block w-full"
                        readonly
                    />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        autofocus
                        placeholder="Password"
                    />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">
                        Confirm Password
                    </Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                    />
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :disabled="processing"
                    data-test="reset-password-button"
                >
                    <Spinner v-if="processing" />
                    Reset password
                </Button>
            </div>
        </Form>
    </AuthBase>
</template>

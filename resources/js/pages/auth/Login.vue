<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    Bell,
    LayoutDashboard,
    Shield,
    User,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AlertError from '@/components/AlertError.vue';
import TextLink from '@/components/TextLink.vue';
import PasswordInput from '@/components/auth/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

const loginFeatures = [
    { icon: Shield, title: 'Secure access', description: 'Sign in safely with your credentials' },
    { icon: LayoutDashboard, title: 'Your dashboard', description: 'Quick access to your HR tools' },
    { icon: Bell, title: 'Real-time updates', description: 'Stay informed with notifications' },
    { icon: User, title: 'Profile management', description: 'Update your info anytime' },
];

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
    errors?: Record<string, string>;
}>();

const remember = ref(false);

const errorsList = (errors: Record<string, string>) => {
    return Object.values(errors);
};
</script>

<template>
    <AuthBase
        title="Welcome back."
        description="Enter your credentials to sign in to your account."
        :header-link="canRegister ? { href: register().url, label: 'Register' } : undefined"
        :features="loginFeatures"
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <AlertError
                v-if="Object.keys(errors).length"
                :errors="errorsList(errors)"
                class="mb-4 border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20"
            />
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="username">Username</Label>
                    <Input
                        id="username"
                        type="text"
                        name="username"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="username"
                        placeholder="Enter your username"
                    />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm font-medium text-[#013CFC] hover:text-[#0031BC]"
                            :tabindex="5"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                </div>

                <div class="flex items-center gap-2">
                    <Checkbox
                        id="remember"
                        :checked="remember"
                        :tabindex="3"
                        @update:checked="(val: boolean) => remember = val"
                    />
                    <input type="hidden" name="remember" :value="remember ? '1' : ''" />
                    <Label
                        for="remember"
                        class="cursor-pointer text-sm font-normal"
                    >
                        Remember me
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Sign In
                </Button>
            </div>

            <div
                v-if="canRegister"
                class="text-center text-sm text-gray-600 dark:text-gray-400"
            >
                Don't have an account?
                <TextLink
                    :href="register().url"
                    class="font-medium text-[#013CFC] hover:text-[#0031BC]"
                    :tabindex="6"
                >
                    Register here
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>

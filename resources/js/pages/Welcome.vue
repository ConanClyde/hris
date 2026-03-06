<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome"> </Head>
    <div
        class="flex min-h-dvh flex-col items-center justify-center bg-neutral-50 p-6 dark:bg-neutral-950"
    >
        <div class="w-full max-w-md text-center">
            <div class="mb-8 flex justify-center">
                <img
                    src="/images/hris_logo.png"
                    alt="HRIS Logo"
                    class="size-16 object-contain"
                />
            </div>

            <h1
                class="mb-2 text-3xl font-bold text-neutral-900 dark:text-neutral-50"
            >
                Welcome to HRIS
            </h1>
            <p class="mb-8 text-neutral-600 dark:text-neutral-400">
                Human Resource Information System
            </p>

            <div class="flex flex-col gap-3">
                <Link
                    :href="login()"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-brand px-4 py-3 font-medium text-white transition-colors hover:bg-brand-dark"
                >
                    Sign In
                </Link>
                <Link
                    v-if="canRegister"
                    :href="register()"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-neutral-300 bg-white px-4 py-3 font-medium text-neutral-700 transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700"
                >
                    Register
                </Link>
            </div>
        </div>
    </div>
</template>

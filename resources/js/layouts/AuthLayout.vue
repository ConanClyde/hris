<script setup lang="ts">
import type { AuthFeature } from '@/layouts/auth/AuthSplitLayout.vue';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

defineProps<{
    title?: string;
    description?: string;
    backHref?: string;
    showProgress?: boolean;
    progressStep?: number;
    totalSteps?: number;
    headerLink?: { href: string; label: string };
    stepLabel?: string;
    features?: AuthFeature[];
}>();
</script>

<template>
    <AuthSplitLayout
        :title="title"
        :description="description"
        :back-href="backHref"
        :show-progress="showProgress"
        :progress-step="progressStep"
        :total-steps="totalSteps"
        :header-link="headerLink"
        :step-label="stepLabel"
        :features="features"
    >
        <slot />
    </AuthSplitLayout>
</template>

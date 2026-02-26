<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';
import { Toaster, toast } from 'vue-sonner';
import 'vue-sonner/style.css';
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { useBroadcasting } from '@/composables/useBroadcasting';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
watch(
    () => page.props.flash as { success?: string; error?: string } | undefined,
    (flash) => {
        if (flash?.success) toast.success(flash.success);
        if (flash?.error) toast.error(flash.error);
    },
    { deep: true, immediate: true }
);

const { setupUserListeners, setupAdminListeners, setupHrListeners, setupEmployeeListeners } = useBroadcasting();

onMounted(() => {
    const user = page.props.auth?.user as { id?: number; role?: string } | undefined;
    if (!user?.id) return;

    setupUserListeners(user.id);

    if (user.role === 'admin') setupAdminListeners();
    if (user.role === 'hr') setupHrListeners();

    setupEmployeeListeners();
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
    <Toaster :visible-toasts="2" rich-colors />
</template>

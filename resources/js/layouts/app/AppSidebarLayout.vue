<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
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

const {
    notifications,
    initCountsFromPage,
    loadInitialDropdownItems,
    setupUserListeners,
    setupAdminListeners,
    setupHrListeners,
    setupEmployeeListeners,
} = useBroadcasting();

const notificationsHydrated = ref(false);

watch(
    () => notifications.value[0]?.id,
    (next, prev) => {
        if (!notificationsHydrated.value) return;
        if (next === prev) return;

        const n = notifications.value[0];
        if (!n) return;

        if (n.type === 'success') toast.success(n.title, { description: n.message });
        else if (n.type === 'warning') toast.warning(n.title, { description: n.message });
        else if (n.type === 'error') toast.error(n.title, { description: n.message });
        else toast(n.title, { description: n.message });
    }
);

onMounted(async () => {
    const user = page.props.auth?.user as { id?: number; role?: string } | undefined;
    if (!user?.id) return;

    initCountsFromPage(page.props.auth?.counts as any);
    await loadInitialDropdownItems(user.role);
    notificationsHydrated.value = true;

    setupUserListeners(user.id, user.role);

    if (user.role === 'admin') setupAdminListeners(user.role);
    if (user.role === 'hr' || user.role === 'admin') setupHrListeners(user.role);

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

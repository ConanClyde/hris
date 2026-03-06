<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useBroadcasting } from '@/composables/useBroadcasting';
import { getRoleMenu, type AppRole } from '@/navigation/roleMenus';
import type { NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const page = usePage();
const {
    usersPendingCount,
    notificationsUnreadCount,
    leavesPendingCount,
    trainingsAssignedCount,
    pdsPendingCount,
} = useBroadcasting();

const role = computed<AppRole>(() => {
    const raw = (page.props.auth?.user as { role?: string } | undefined)?.role;
    if (raw === 'admin' || raw === 'hr' || raw === 'employee') return raw;
    return 'employee';
});

const counts = computed(() => {
    const base = (page.props.auth?.counts || {}) as Record<string, any>;

    return {
        ...base,
        users_pending:
            typeof usersPendingCount.value === 'number'
                ? usersPendingCount.value
                : base.users_pending,
        notifications_unread:
            typeof notificationsUnreadCount.value === 'number'
                ? notificationsUnreadCount.value
                : base.notifications_unread,
        leaves_pending:
            typeof leavesPendingCount.value === 'number'
                ? leavesPendingCount.value
                : base.leaves_pending,
        trainings_assigned:
            typeof trainingsAssignedCount.value === 'number'
                ? trainingsAssignedCount.value
                : base.trainings_assigned,
        pds_pending:
            typeof pdsPendingCount.value === 'number'
                ? pdsPendingCount.value
                : base.pds_pending,
    };
});

const menu = computed(() => getRoleMenu(role.value, counts.value));

const mainNavItems = computed<NavItem[]>(() => menu.value.main);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader class="pb-8">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="mainNavItems[0]?.href">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="flex flex-col items-center">
            <NavMain :items="mainNavItems" class="w-full" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

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
import type { NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import { getRoleMenu, type AppRole } from '@/navigation/roleMenus';

const page = usePage();

const role = computed<AppRole>(() => {
    const raw = (page.props.auth?.user as { role?: string } | undefined)?.role;
    if (raw === 'admin' || raw === 'hr' || raw === 'employee') return raw;
    return 'employee';
});

const menu = computed(() => getRoleMenu(role.value));

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

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

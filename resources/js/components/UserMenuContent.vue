<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { LogOut, Settings, User as UserIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { useAuthStore } from '@/composables/useAuthStore';
import { getRoleMenu, type AppRole } from '@/navigation/roleMenus';
import { logout } from '@/routes';
import type { User } from '@/types';

type Props = {
    user: User;
};

const props = defineProps<Props>();

const page = usePage();
const { authUser } = useAuthStore();
const role = computed<AppRole>(() => {
    const raw =
        (authUser.value as { role?: string } | undefined)?.role ??
        (page.props.auth?.user as { role?: string } | undefined)?.role;
    if (raw === 'admin' || raw === 'hr' || raw === 'employee') return raw;
    return 'employee';
});

const menu = computed(() => getRoleMenu(role.value));
const profileUrl = computed(() => menu.value.footer.profile.href);
const settingsUrl = computed(() => menu.value.footer.settings.href);

const displayUser = computed(
    () => authUser.value ?? page.props.auth?.user ?? props.user,
);

const handleLogout = () => {
    router.flushAll();
    localStorage.removeItem('hris-chat-history');
    localStorage.removeItem('hris-chat-history-opt-in');
};
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="displayUser" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link
                class="block w-full cursor-pointer"
                :href="profileUrl"
                prefetch
            >
                <UserIcon class="mr-2 h-4 w-4" />
                Profile
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link
                class="block w-full cursor-pointer"
                :href="settingsUrl"
                prefetch
            >
                <Settings class="mr-2 h-4 w-4" />
                Settings
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full cursor-pointer"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>

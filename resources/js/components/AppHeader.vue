<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Folder,
    LayoutGrid,
    Menu,
    Search,
    Bell,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useAuthStore } from '@/composables/useAuthStore';
import { useBroadcasting } from '@/composables/useBroadcasting';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { getInitials, getInitialsFromName } from '@/composables/useInitials';
import { toUrl } from '@/lib/utils';
import { dashboard } from '@/routes';
import admin from '@/routes/admin';
import employee from '@/routes/employee';
import hr from '@/routes/hr';
import type { BreadcrumbItem, NavItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const { authUser } = useAuthStore();
const auth = computed(() => ({
    user: authUser.value ?? page.props.auth?.user,
}));
const showAvatar = computed(
    () =>
        typeof auth.value?.user?.avatar === 'string' &&
        auth.value.user.avatar.trim() !== '',
);
const notificationsHref = computed(() => {
    const role = (auth.value?.user as { role?: string } | undefined)?.role;
    if (role === 'admin') return admin.notifications.url();
    if (role === 'hr') return hr.notifications.url();
    return employee.notifications.url();
});
const role = computed(
    () =>
        (auth.value?.user as { role?: string } | undefined)?.role ?? 'employee',
);

const {
    notifications,
    notificationsUnreadCount,
    markAllAsRead,
    markAsRead,
    refreshNotificationsDropdown,
} = useBroadcasting();

const latestNotifications = computed(() => notifications.value.slice(0, 10));
const unreadBadge = computed(() => {
    const count = notificationsUnreadCount.value;
    return typeof count === 'number' && count > 0 ? count : 0;
});

function csrfToken(): string {
    return (
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || ''
    );
}

async function markAllServerRead() {
    const safeRole = String(role.value || '').toLowerCase();
    const url =
        safeRole === 'admin'
            ? '/admin/notifications/mark-all-read'
            : safeRole === 'hr'
              ? '/hr/notifications/mark-all-read'
              : '/employee/notifications/mark-all-read';

    await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    markAllAsRead();
    await refreshNotificationsDropdown(role.value);
}

async function openNotification(n: {
    id: string;
    data?: Record<string, unknown>;
}) {
    const safeRole = String(role.value || '').toLowerCase();
    const url =
        safeRole === 'admin'
            ? `/admin/notifications/${n.id}/mark-as-read`
            : safeRole === 'hr'
              ? `/hr/notifications/${n.id}/mark-as-read`
              : `/employee/notifications/${n.id}/mark-as-read`;

    await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    markAsRead(n.id);
    await refreshNotificationsDropdown(role.value);

    const redirect =
        (n.data?.redirect_url as string | undefined) ??
        (n.data?.url as string | undefined) ??
        undefined;
    if (redirect) {
        window.location.href = redirect;
    } else {
        window.location.href = notificationsHref.value;
    }
}
const { isCurrentUrl, whenCurrentUrl } = useCurrentUrl();

const activeItemStyles =
    'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const rightNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <div>
        <div class="border-b border-sidebar-border/80">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="mr-2 h-9 w-9"
                            >
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6">
                            <SheetTitle class="sr-only"
                                >Navigation Menu</SheetTitle
                            >
                            <SheetHeader class="flex justify-start text-left">
                                <AppLogoIcon
                                    class="size-6 fill-current text-black dark:text-white"
                                />
                            </SheetHeader>
                            <div
                                class="flex h-full flex-1 flex-col justify-between space-y-4 py-6"
                            >
                                <nav class="-mx-3 space-y-1">
                                    <Link
                                        v-for="item in mainNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                                        :class="
                                            whenCurrentUrl(
                                                item.href,
                                                activeItemStyles,
                                            )
                                        "
                                    >
                                        <component
                                            v-if="item.icon"
                                            :is="item.icon"
                                            class="h-5 w-5"
                                        />
                                        {{ item.title }}
                                    </Link>
                                </nav>
                                <div class="flex flex-col space-y-4">
                                    <a
                                        v-for="item in rightNavItems"
                                        :key="item.title"
                                        :href="toUrl(item.href)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center space-x-2 text-sm font-medium"
                                    >
                                        <component
                                            v-if="item.icon"
                                            :is="item.icon"
                                            class="h-5 w-5"
                                        />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <Link :href="dashboard()" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    <NavigationMenu class="ml-10 flex h-full items-stretch">
                        <NavigationMenuList
                            class="flex h-full items-stretch space-x-2"
                        >
                            <NavigationMenuItem
                                v-for="(item, index) in mainNavItems"
                                :key="index"
                                class="relative flex h-full items-center"
                            >
                                <Link
                                    :class="[
                                        navigationMenuTriggerStyle(),
                                        whenCurrentUrl(
                                            item.href,
                                            activeItemStyles,
                                        ),
                                        'h-9 cursor-pointer px-3',
                                    ]"
                                    :href="item.href"
                                >
                                    <component
                                        v-if="item.icon"
                                        :is="item.icon"
                                        class="mr-2 h-4 w-4"
                                    />
                                    {{ item.title }}
                                </Link>
                                <div
                                    v-if="isCurrentUrl(item.href)"
                                    class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-black dark:bg-white"
                                ></div>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <div class="relative flex items-center space-x-1">
                        <Button
                            variant="ghost"
                            size="icon"
                            class="group h-9 w-9 cursor-pointer"
                        >
                            <Search
                                class="size-5 opacity-80 group-hover:opacity-100"
                            />
                        </Button>

                        <div class="hidden space-x-1 lg:flex">
                            <template
                                v-for="item in rightNavItems"
                                :key="item.title"
                            >
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                as-child
                                                class="group h-9 w-9 cursor-pointer"
                                            >
                                                <a
                                                    :href="toUrl(item.href)"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    <span class="sr-only">{{
                                                        item.title
                                                    }}</span>
                                                    <component
                                                        :is="item.icon"
                                                        class="size-5 opacity-80 group-hover:opacity-100"
                                                    />
                                                </a>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>{{ item.title }}</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </template>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <button
                                type="button"
                                class="relative inline-flex h-9 w-9 items-center justify-center rounded-full border border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-800"
                                :aria-label="`Notifications (${unreadBadge})`"
                            >
                                <Bell class="size-5" />
                                <span
                                    v-if="unreadBadge > 0"
                                    class="absolute -top-0.5 -right-0.5 inline-flex min-w-5 items-center justify-center rounded-full bg-red-600 px-1.5 text-[11px] leading-5 font-semibold text-white"
                                >
                                    {{ unreadBadge > 99 ? '99+' : unreadBadge }}
                                </span>
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-96 p-0">
                            <div
                                class="flex items-center justify-between border-b px-3 py-2"
                            >
                                <div class="text-sm font-semibold">
                                    Notifications
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        class="h-8"
                                        @click="markAllServerRead"
                                    >
                                        Mark all read
                                    </Button>
                                    <Link
                                        :href="notificationsHref"
                                        class="text-xs text-muted-foreground hover:text-foreground"
                                    >
                                        View all
                                    </Link>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-auto">
                                <button
                                    v-for="n in latestNotifications"
                                    :key="n.id"
                                    type="button"
                                    class="flex w-full items-start gap-3 border-b px-3 py-2 text-left hover:bg-gray-50 dark:hover:bg-neutral-800"
                                    :class="
                                        !n.read
                                            ? 'bg-blue-50/60 dark:bg-blue-900/10'
                                            : ''
                                    "
                                    @click="openNotification(n)"
                                >
                                    <div class="min-w-0 flex-1">
                                        <div
                                            class="flex items-center justify-between gap-2"
                                        >
                                            <p
                                                class="truncate text-sm font-medium"
                                            >
                                                {{ n.title }}
                                            </p>
                                            <span
                                                v-if="!n.read"
                                                class="h-2 w-2 shrink-0 rounded-full bg-blue-600"
                                            ></span>
                                        </div>
                                        <p
                                            class="mt-0.5 line-clamp-2 text-xs text-muted-foreground"
                                        >
                                            {{ n.message }}
                                        </p>
                                        <p
                                            class="mt-1 text-[11px] text-muted-foreground"
                                        >
                                            {{
                                                new Date(
                                                    n.created_at,
                                                ).toLocaleString()
                                            }}
                                        </p>
                                    </div>
                                </button>
                                <div
                                    v-if="!latestNotifications.length"
                                    class="px-3 py-10 text-center text-sm text-muted-foreground"
                                >
                                    No notifications yet.
                                </div>
                            </div>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
                            >
                                <Avatar
                                    class="size-8 overflow-hidden rounded-full"
                                >
                                    <AvatarImage
                                        v-if="showAvatar"
                                        :src="auth.user.avatar!"
                                        :alt="auth.user.name"
                                    />
                                    <AvatarFallback
                                        class="rounded-full bg-foreground font-semibold text-background"
                                    >
                                        {{
                                            getInitialsFromName({
                                                first_name: (auth.user as any)
                                                    ?.first_name,
                                                last_name: (auth.user as any)
                                                    ?.last_name,
                                            }) || getInitials(auth.user?.name)
                                        }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div
            v-if="props.breadcrumbs.length > 1"
            class="flex w-full border-b border-sidebar-border/70"
        >
            <div
                class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl"
            >
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>

import {
    Bell,
    BookOpen,
    Bot,
    Calendar,
    FileText,
    LayoutDashboard,
    LineChart,
    Network,
    Users,
    DatabaseBackup,
    Settings,
    User,
} from 'lucide-vue-next';
import { aiChatbot } from '@/routes';
import admin from '@/routes/admin';
import employee from '@/routes/employee';
import hr from '@/routes/hr';
import type { NavItem } from '@/types';

export type AppRole = 'admin' | 'hr' | 'employee';

type RoleMenuConfig = {
    main: NavItem[];
    footer: {
        profile: NavItem;
        settings: NavItem;
    };
};

export function getRoleMenu(
    role: AppRole,
    counts: Record<string, any> = {},
): RoleMenuConfig {
    const notificationsBadge =
        typeof counts.notifications_unread === 'number' &&
        counts.notifications_unread > 0
            ? counts.notifications_unread
            : undefined;

    if (role === 'admin') {
        return {
            main: [
                {
                    title: 'Dashboard',
                    href: admin.dashboard().url,
                    icon: LayoutDashboard,
                },
                {
                    title: 'Calendar',
                    href: admin.calendar().url,
                    icon: Calendar,
                },
                {
                    title: 'Manage Users',
                    href: admin.users().url,
                    icon: Users,
                    badge: counts.users_pending || undefined,
                },
                {
                    title: 'Activity Logs',
                    href: admin.activityLogs.index().url,
                    icon: BookOpen,
                },
                {
                    title: 'Performance',
                    href: admin.performance.index().url,
                    icon: LineChart,
                },
                {
                    title: 'Reports & Analytics',
                    href: admin.reports().url,
                    icon: FileText,
                },
                {
                    title: 'Backup',
                    href: admin.backup.index().url,
                    icon: DatabaseBackup,
                },
                {
                    title: 'Announcements',
                    href: admin.posts.index().url,
                    icon: FileText,
                },
                {
                    title: 'Notifications',
                    href: admin.notifications().url,
                    icon: Bell,
                    badge: notificationsBadge,
                },
                {
                    title: 'AI Helpdesk',
                    href: aiChatbot().url,
                    icon: Bot,
                },
            ],
            footer: {
                profile: {
                    title: 'Profile',
                    href: admin.profile().url,
                    icon: User,
                },
                settings: {
                    title: 'Settings',
                    href: admin.settings().url,
                    icon: Settings,
                },
            },
        };
    }

    if (role === 'hr') {
        return {
            main: [
                {
                    title: 'Dashboard',
                    href: hr.dashboard().url,
                    icon: LayoutDashboard,
                },
                { title: 'Calendar', href: hr.calendar().url, icon: Calendar },
                {
                    title: 'Manage Users',
                    href: hr.users.index().url,
                    icon: Users,
                    badge: counts.users_pending || undefined,
                },
                {
                    title: 'Organizational Chart',
                    href: hr.orgchart().url,
                    icon: Network,
                },
                {
                    title: 'PDS Management',
                    href: hr.pds.index().url,
                    icon: FileText,
                    badge: counts.pds_pending || undefined,
                },
                {
                    title: 'Leave Applications',
                    href: hr.leaveApplications.index().url,
                    icon: FileText,
                    badge: counts.leaves_pending || undefined,
                },
                {
                    title: 'Learning & Development',
                    href: hr.training.index().url,
                    icon: BookOpen,
                    badge: counts.trainings_assigned || undefined,
                },
                {
                    title: 'Announcements',
                    href: hr.posts.index().url,
                    icon: FileText,
                },
                {
                    title: 'Reports & Analytics',
                    href: hr.reports().url,
                    icon: LineChart,
                },
                {
                    title: 'Notifications',
                    href: hr.notifications().url,
                    icon: Bell,
                    badge: notificationsBadge,
                },
                {
                    title: 'AI Helpdesk',
                    href: aiChatbot().url,
                    icon: Bot,
                },
            ],
            footer: {
                profile: {
                    title: 'Profile',
                    href: hr.profile().url,
                    icon: User,
                },
                settings: {
                    title: 'Settings',
                    href: hr.settings().url,
                    icon: Settings,
                },
            },
        };
    }

    return {
        main: [
            {
                title: 'Dashboard',
                href: employee.dashboard().url,
                icon: LayoutDashboard,
            },
            { title: 'PDS', href: employee.pds.index().url, icon: FileText },
            {
                title: 'Calendar',
                href: employee.calendar().url,
                icon: Calendar,
            },
            {
                title: 'Leave Applications',
                href: employee.leaveApplications.index().url,
                icon: FileText,
                badge: counts.leaves_pending || undefined,
            },
            {
                title: 'Learning & Development',
                href: employee.training.index().url,
                icon: BookOpen,
                badge: counts.trainings_assigned || undefined,
            },
            {
                title: 'Announcements',
                href: employee.posts.index().url,
                icon: FileText,
            },
            {
                title: 'Notifications',
                href: employee.notifications().url,
                icon: Bell,
                badge: notificationsBadge,
            },
            {
                title: 'Activity Logs',
                href: '/employee/activity-logs',
                icon: BookOpen,
            },
            {
                title: 'AI Helpdesk',
                href: aiChatbot().url,
                icon: Bot,
            },
        ],
        footer: {
            profile: {
                title: 'Profile',
                href: employee.profile().url,
                icon: User,
            },
            settings: {
                title: 'Settings',
                href: employee.settings().url,
                icon: Settings,
            },
        },
    };
}

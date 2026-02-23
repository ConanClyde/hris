import {
    Bell,
    BookOpen,
    Calendar,
    FileText,
    LayoutDashboard,
    LineChart,
    Shield,
    Users,
    DatabaseBackup,
    Settings,
    User,
} from 'lucide-vue-next';
import type { NavItem } from '@/types';
import admin from '@/routes/admin';
import employee from '@/routes/employee';
import hr from '@/routes/hr';

export type AppRole = 'admin' | 'hr' | 'employee';

type RoleMenuConfig = {
    main: NavItem[];
    footer: {
        profile: NavItem;
        settings: NavItem;
    };
};

export function getRoleMenu(role: AppRole): RoleMenuConfig {
    if (role === 'admin') {
        return {
            main: [
                { title: 'Dashboard', href: admin.dashboard().url, icon: LayoutDashboard },
                { title: 'Calendar', href: admin.calendar().url, icon: Calendar },
                { title: 'Manage Users', href: admin.users().url, icon: Users },
                { title: 'Activity Logs', href: admin.activityLogs.index().url, icon: BookOpen },
                { title: 'Performance', href: admin.performance.index().url, icon: LineChart },
                { title: 'Reports & Analytics', href: admin.reports().url, icon: FileText },
                { title: 'Backup', href: admin.backup.index().url, icon: DatabaseBackup },
                { title: 'Global Notices', href: admin.notices.index().url, icon: Shield },
                { title: 'Notifications', href: admin.notifications().url, icon: Bell },
            ],
            footer: {
                profile: { title: 'Profile', href: admin.profile().url, icon: User },
                settings: { title: 'Settings', href: admin.settings().url, icon: Settings },
            },
        };
    }

    if (role === 'hr') {
        return {
            main: [
                { title: 'Dashboard', href: hr.dashboard().url, icon: LayoutDashboard },
                { title: 'Calendar', href: hr.calendar().url, icon: Calendar },
                { title: 'Manage Users', href: hr.users.index().url, icon: Users },
                { title: 'PDS Management', href: hr.pds.index().url, icon: FileText },
                { title: 'Leave Applications', href: hr.leaveApplications.index().url, icon: FileText },
                { title: 'Learning & Development', href: hr.training.index().url, icon: BookOpen },
                { title: 'Reports & Analytics', href: hr.reports().url, icon: LineChart },
                { title: 'Global Notices', href: hr.notices.index().url, icon: Shield },
                { title: 'Notifications', href: hr.notifications().url, icon: Bell },
            ],
            footer: {
                profile: { title: 'Profile', href: hr.profile().url, icon: User },
                settings: { title: 'Settings', href: hr.settings().url, icon: Settings },
            },
        };
    }

    return {
        main: [
            { title: 'Dashboard', href: employee.dashboard().url, icon: LayoutDashboard },
            { title: 'PDS', href: employee.pds.index().url, icon: FileText },
            { title: 'Calendar', href: employee.calendar().url, icon: Calendar },
            { title: 'Leave Applications', href: employee.leaveApplications.index().url, icon: FileText },
            { title: 'Learning & Development', href: employee.training.index().url, icon: BookOpen },
            { title: 'Notifications', href: employee.notifications().url, icon: Bell },
        ],
        footer: {
            profile: { title: 'Profile', href: employee.profile().url, icon: User },
            settings: { title: 'Settings', href: employee.settings().url, icon: Settings },
        },
    };
}

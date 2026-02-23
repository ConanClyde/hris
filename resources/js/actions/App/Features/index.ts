import Leave from './Leave'
import Training from './Training'
import Notices from './Notices'
import Employees from './Employees'
import Dashboard from './Dashboard'
import Calendar from './Calendar'
import Users from './Users'
import ActivityLogs from './ActivityLogs'
import Backup from './Backup'
import Notifications from './Notifications'
import Auth from './Auth'
import Pds from './Pds'
const Features = {
    Leave: Object.assign(Leave, Leave),
Training: Object.assign(Training, Training),
Notices: Object.assign(Notices, Notices),
Employees: Object.assign(Employees, Employees),
Dashboard: Object.assign(Dashboard, Dashboard),
Calendar: Object.assign(Calendar, Calendar),
Users: Object.assign(Users, Users),
ActivityLogs: Object.assign(ActivityLogs, ActivityLogs),
Backup: Object.assign(Backup, Backup),
Notifications: Object.assign(Notifications, Notifications),
Auth: Object.assign(Auth, Auth),
Pds: Object.assign(Pds, Pds),
}

export default Features
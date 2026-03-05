import Leave from './Leave'
import Training from './Training'
import Notices from './Notices'
import Employees from './Employees'
import Auth from './Auth'
import AIChatbot from './AIChatbot'
import Dashboard from './Dashboard'
import Calendar from './Calendar'
import Users from './Users'
import ActivityLogs from './ActivityLogs'
import Backup from './Backup'
import Notifications from './Notifications'
import Posts from './Posts'
import Pds from './Pds'
const Features = {
    Leave: Object.assign(Leave, Leave),
Training: Object.assign(Training, Training),
Notices: Object.assign(Notices, Notices),
Employees: Object.assign(Employees, Employees),
Auth: Object.assign(Auth, Auth),
AIChatbot: Object.assign(AIChatbot, AIChatbot),
Dashboard: Object.assign(Dashboard, Dashboard),
Calendar: Object.assign(Calendar, Calendar),
Users: Object.assign(Users, Users),
ActivityLogs: Object.assign(ActivityLogs, ActivityLogs),
Backup: Object.assign(Backup, Backup),
Notifications: Object.assign(Notifications, Notifications),
Posts: Object.assign(Posts, Posts),
Pds: Object.assign(Pds, Pds),
}

export default Features
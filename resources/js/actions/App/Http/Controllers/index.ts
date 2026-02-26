import Auth from './Auth'
import AttendanceController from './AttendanceController'
import Settings from './Settings'
const Controllers = {
    Auth: Object.assign(Auth, Auth),
AttendanceController: Object.assign(AttendanceController, AttendanceController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers
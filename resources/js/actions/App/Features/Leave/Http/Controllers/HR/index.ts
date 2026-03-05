import LeaveController from './LeaveController'
import LeaveCreditController from './LeaveCreditController'
import LeaveReportsController from './LeaveReportsController'
const HR = {
    LeaveController: Object.assign(LeaveController, LeaveController),
LeaveCreditController: Object.assign(LeaveCreditController, LeaveCreditController),
LeaveReportsController: Object.assign(LeaveReportsController, LeaveReportsController),
}

export default HR
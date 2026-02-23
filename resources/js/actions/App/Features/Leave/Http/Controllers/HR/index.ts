import LeaveController from './LeaveController'
import LeaveCreditController from './LeaveCreditController'
const HR = {
    LeaveController: Object.assign(LeaveController, LeaveController),
LeaveCreditController: Object.assign(LeaveCreditController, LeaveCreditController),
}

export default HR
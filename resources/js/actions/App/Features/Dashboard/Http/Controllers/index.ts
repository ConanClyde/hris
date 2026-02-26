import DashboardController from './DashboardController'
import PerformanceController from './PerformanceController'
import ReportsController from './ReportsController'
const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
PerformanceController: Object.assign(PerformanceController, PerformanceController),
ReportsController: Object.assign(ReportsController, ReportsController),
}

export default Controllers
import DashboardController from './DashboardController'
import PerformanceController from './PerformanceController'
import ReportsController from './ReportsController'
import CustomReportController from './CustomReportController'
import OnboardingController from './OnboardingController'
const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
PerformanceController: Object.assign(PerformanceController, PerformanceController),
ReportsController: Object.assign(ReportsController, ReportsController),
CustomReportController: Object.assign(CustomReportController, CustomReportController),
OnboardingController: Object.assign(OnboardingController, OnboardingController),
}

export default Controllers
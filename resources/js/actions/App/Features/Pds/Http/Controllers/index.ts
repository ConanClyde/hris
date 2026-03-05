import HR from './HR'
import PdsExportController from './PdsExportController'
import Employee from './Employee'
const Controllers = {
    HR: Object.assign(HR, HR),
PdsExportController: Object.assign(PdsExportController, PdsExportController),
Employee: Object.assign(Employee, Employee),
}

export default Controllers
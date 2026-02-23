import HR from './HR'
import Employee from './Employee'
const Controllers = {
    HR: Object.assign(HR, HR),
Employee: Object.assign(Employee, Employee),
}

export default Controllers
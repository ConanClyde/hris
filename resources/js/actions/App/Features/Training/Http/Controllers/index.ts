import Api from './Api'
import HR from './HR'
import Employee from './Employee'
const Controllers = {
    Api: Object.assign(Api, Api),
HR: Object.assign(HR, HR),
Employee: Object.assign(Employee, Employee),
}

export default Controllers
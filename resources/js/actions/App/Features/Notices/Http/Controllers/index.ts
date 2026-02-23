import Api from './Api'
import Admin from './Admin'
import HR from './HR'
const Controllers = {
    Api: Object.assign(Api, Api),
Admin: Object.assign(Admin, Admin),
HR: Object.assign(HR, HR),
}

export default Controllers
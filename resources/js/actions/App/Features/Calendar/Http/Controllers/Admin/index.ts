import CalendarController from './CalendarController'
import CustomHolidayController from './CustomHolidayController'
const Admin = {
    CalendarController: Object.assign(CalendarController, CalendarController),
CustomHolidayController: Object.assign(CustomHolidayController, CustomHolidayController),
}

export default Admin
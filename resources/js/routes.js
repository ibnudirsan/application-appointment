import Dashboard from "./components/Dashboard.vue";
import ListAppointments from "./pages/apointments/apointments.vue";
import AppointmentsForm from "./pages/apointments/apointmentForm.vue";
import UsersList from "./pages/users/UserList.vue";
import ListSettings from "./pages/settings/UpdateSetting.vue";
import UpdateProfile from "./pages/profile/UpdateProfile.vue";

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard
    },

    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments
    },

    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentsForm
    },

    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentsForm
    },

    {
        path: '/admin/users',
        name: 'admin.users',
        component: UsersList
    },

    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: ListSettings
    },

    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile
    }
]
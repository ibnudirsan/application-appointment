import Dashboard from "./components/Dashboard.vue";
import ListApointments from "./pages/apointments/apointments.vue";
import ListUsers from "./pages/users/ListUser.vue";
import ListSettings from "./pages/settings/UpdateSetting.vue";
import UpdateProfile from "./pages/profile/UpdateProfile.vue";

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard
    },

    {
        path: '/admin/apointments',
        name: 'admin.apointments',
        component: ListApointments
    },

    {
        path: '/admin/users',
        name: 'admin.users',
        component: ListUsers
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
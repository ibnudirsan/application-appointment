<script setup>
import { onMounted, ref } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import SidebarLeft from './components/SidebarLeft.vue';
import SidebarRight from './components/SidebarRight.vue';
import AppFooter from './components/AppFooter.vue';

const setting = ref(null);
const authUser = ref(null);

const fetchSettings = () => {
    axios.get('/api/settings')
        .then((response) => {
            setting.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        })
}

const fetchAuthUser = () => {
    axios.get('/api/profile')
        .then((response) => {
            authUser.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        })
}

const logout = () => {
    axios.post('/logout')
        .then((response) => {
            window.location.href = '/login';
        })
        .catch((error) => {
            console.log(error);
        })
}

onMounted(() => {
    fetchSettings();
    fetchAuthUser();
})

</script>
<template>
    <div class="wrapper" id="app">

        <AppNavbar />

        <SidebarLeft :authUser="authUser" :setting="setting" />

        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        
        <SidebarRight />

        <AppFooter :setting="setting"/>
    </div>
</template>
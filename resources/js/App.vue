<script setup>
import { onMounted, ref, computed } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import SidebarLeft from './components/SidebarLeft.vue';
import SidebarRight from './components/SidebarRight.vue';
import AppFooter from './components/AppFooter.vue';
import { useAuthUserStore } from './store/AuthUserStore';
import { useSettingStore } from './store/settingStore';

const authUserStore = useAuthUserStore();
const settingStore = useSettingStore();

const currentThemeMode = computed(() => {
    return settingStore.theme === 'dark' ? 'dark-mode' : '';
})
</script>
<template>
    <div v-if="authUserStore.user.name !== ''" class="wrapper" :class="currentThemeMode" id="app">

        <AppNavbar />

        <SidebarLeft />

        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        
        <SidebarRight />

        <AppFooter />
    </div>

    <div v-else class="login-page" :class="currentThemeMode">
        <router-view></router-view>
    </div>
</template>
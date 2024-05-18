import { defineStore } from "pinia";
import { ref } from "vue";
import { useStorage } from '@vueuse/core'

export const useSettingStore = defineStore("SettingStore", () => {
    const settings = ref({
        app_name: "",
    });
    const theme = useStorage('SettingStore:theme', ref('light'));

    const getSetting = async () => {
      await axios.get("/api/settings")
            .then((response) => {
                settings.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    };

    const changeTheme = () => {
        theme.value = theme.value === 'light' ? 'dark' : 'light';
    }
    
    return { settings, getSetting, theme, changeTheme };
});

import { defineStore } from "pinia";

export const useSettingStore = defineStore("SettingStore", () => {
    const settings = ref({
        app_name: "",
    });

    const getSetting = () => {
        axios.get("/api/settings")
            .then((response) => {
                settings.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    };
    return { settings, getSetting };
});

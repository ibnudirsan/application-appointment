<script setup>

import { onMounted, ref } from 'vue';
import { useToastr } from '@/toastr';

const setting = ref([]);
const toastr = useToastr();
const errors = ref();

const getSetting = () => {
    axios.get('/api/settings')
    .then((response) => {
        setting.value = response.data;
    })
    .catch((error) => {
        console.log(error);
    })
}

const updateSettings = () => {
    errors.value = [];
    axios.post('/api/settings', setting.value)
    .then((response) => {
        getSetting();
        toastr.success('Settings updated successfully!');
    })
    .catch((error) => {
        if(error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
    })
}

onMounted(() => {
    getSetting();
})

</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Update Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">General Setting</h3>
                                </div>

                                <form @submit.prevent="updateSettings()">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="appName">App Display Name</label>
                                            <input v-model="setting.app_name" type="text" class="form-control" id="appName"
                                                placeholder="Enter app display name">
                                                <span v-if="errors && errors.app_name" class="text-danger text-sm">{{ errors.app_name[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="dateFormat">Date Format</label>
                                            <select v-model="setting.date_format" class="form-control">
                                                <option value="m/d/Y">MM/DD/YYYY</option>
                                                <option value="d/m/Y">DD/MM/YYYY</option>
                                                <option value="Y-m-d">YYYY-MM-DD</option>
                                                <option value="F j, Y">Month DD, YYYY</option>
                                                <option value="j F Y">DD Month YYYY</option>
                                            </select>
                                            <span v-if="errors && errors.date_format" class="text-danger text-sm">{{ errors.date_format[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="paginationLimit">Pagination Limit</label>
                                            <input v-model="setting.pagination_limit" type="text" class="form-control" id="paginationLimit"
                                                placeholder="Enter pagination limit">
                                                <span v-if="errors && errors.pagination_limit" class="text-danger text-sm">{{ errors.pagination_limit[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-save mr-1"></i>Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
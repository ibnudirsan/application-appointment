<script setup>

import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

const form = reactive({
    title: '',
    client_id: '',
    start_time: '',
    end_time: '',
    description: '',
});

const clients = ref();
const editMode = ref(false);

const router = useRouter();
const route = useRoute();
const toastr = useToastr();

const handleSubmit = (value, actions) => {
    if(editMode.value) {
        updateAppointment(value, actions);
    } else {
        createAppointment(value, actions);
    }
};

const createAppointment = (valuse, actions) => {
    axios.post('/api/appointments/create', form)
        .then((response) => {
            router.push('/admin/appointments');
            toastr.success('Appointment created successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
}

const updateAppointment = (value, actions) => {
    axios.put(`/api/appointments/${route.params.id}/update`, form)
        .then((response) => {
            router.push('/admin/appointments');
            toastr.success('Appointment updated successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
}

const getClinets = () => {
    axios.get('/api/clients')
    .then((response) => {
        clients.value = response.data;
    })
    .catch((error) => {
        console.log(error);
    })
}

const getAppointments = () => {
    axios.get(`/api/appointments/${route.params.id}/edit`)
    .then(({data}) => {
        form.title = data.title;
        form.client_id = data.client_id;
        form.start_time = data.formatted_start_time;
        form.end_time = data.formatted_end_time;
        form.description = data.description;
    })
    .catch((error) => {
        console.log(error);
    })
}

onMounted(() => {
    if(route.name === 'admin.appointments.edit') {
        editMode.value = true;
        getAppointments();
    }
        flatpickr(".flatpickr", {
            enableTime: true,
            dateFormat: "Y-m-d h:i K",
            defaultHour: 10,
        })
        getClinets();
})
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <span v-if="editMode">
                        <h1 class="m-0">Edit Appointments</h1>
                    </span>
                    <span v-else>
                        <h1 class="m-0">Create Appointments</h1>
                    </span>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot:default="{ errors }">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input v-model="form.title" type="text" class="form-control" :class="{ 'is-invalid': errors.title }" id="title" placeholder="Enter Title">
                                            <span class="invalid-feedback">{{ errors.title }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client Name</label>
                                            <select v-model="form.client_id" id="client" class="form-control" :class="{ 'is-invalid': errors.client_id }">
                                                <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.first_name }} {{ client.last_name }}</option>
                                            </select>
                                            <span class="invalid-feedback">{{ errors.client_id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Start Time</label>
                                            <input v-model="form.start_time" type="text" class="form-control flatpickr" :class="{ 'is-invalid': errors.start_time }" id="start_time">
                                            <span class="invalid-feedback">{{ errors.start_time }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_time">End Time</label>
                                            <input v-model="form.end_time" type="text" class="form-control flatpickr" :class="{ 'is-invalid': errors.end_time }" id="end_time">
                                            <span class="invalid-feedback">{{ errors.end_time }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control" id="description" rows="3"
                                        placeholder="Enter Description" :class="{ 'is-invalid': errors.description }"></textarea>
                                    <span class="invalid-feedback">{{ errors.description }}</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
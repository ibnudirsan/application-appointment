<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { RouterLink } from 'vue-router';
import Swal from 'sweetalert2';
import { result } from 'lodash';
import Preloader from '../../components/Preloader.vue';

const appointmentStatus = ref([]);
const appointments = ref([]);
const selectedStatus = ref();
const loading = ref(false);

const getAppointmentsStatus = () => {
    axios.get('/api/appointments-status')
        .then((response) => {
            appointmentStatus.value = response.data;
        })
}

const getAppointments = (status) => {
    loading.value = true;
    selectedStatus.value = status;
    const params = {};
    if (status) {
        params.status = status;
    }
    axios.get('/api/appointments', {
        params: params,
    })
        .then((response) => {
            appointments.value = response.data;
            loading.value = false;
        })
        .catch((error) => {
            console.log(error);
        })
}

const appointmentsCount = computed(() => {
    return appointmentStatus.value.map(status => status.count).reduce((acc, value) => acc + value, 0);
});

const updateAppointmentsStatusCount = (id) => {
    const CountAppointmentStatusDelete = appointments.value.data.find(appointment => appointment.id === id).status.name;
    const StatusUpdate = appointmentStatus.value.find(status => status.name === CountAppointmentStatusDelete);
    StatusUpdate.count--;

}

const deleteAppointment = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/appointments/${id}`)
                .then((response) => {
                    updateAppointmentsStatusCount(id);
                    getAppointments();
                })
                .catch((error) => {
                    appointments.value.data = appointments.value.data.filter(appointment => appointment.id !== id);
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000
                    });
                })
        }
    });
}

onMounted(() => {
    getAppointments();
    getAppointmentsStatus();
})
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Apointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Apointments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <RouterLink :to="'/admin/appointments/create'">
                                <button class="btn btn-primary">
                                    <i class="fa fa-plus-circle mr-1"></i>
                                    Add New
                                    Appointment
                                </button>
                            </RouterLink>
                        </div>
                        <div class="btn-group">
                            <button @click="getAppointments()" type="button" class="btn"
                                :class="[typeof selectedStatus === 'undefined' ? 'btn btn-secondary' : 'btn btn-default']">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-info">{{ appointmentsCount }}</span>
                            </button>
                            <button v-for="status in appointmentStatus" :key="status.id"
                                @click="getAppointments(status.value)" type="button"
                                :class="[selectedStatus === status.value ? 'btn btn-secondary' : 'btn btn-default']">
                                <span class="mr-1">{{ status.name }}</span>
                                <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count
                                    }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Client Name</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(appointment, index) in appointments.data" :key="appointment.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ appointment.client.first_name }} {{ appointment.client.last_name }}</td>
                                        <td>{{ appointment.start_time }}</td>
                                        <td>{{ appointment.end_time }}</td>
                                        <td>
                                            <span class="badge" :class="`badge-${appointment.status.color}`">
                                                {{ appointment.status.name }}
                                            </span>
                                        </td>
                                        <td>
                                            <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>

                                            <a href="#" @click="$event => deleteAppointment(appointment.id)">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Preloader :loading="loading"/>
</template>
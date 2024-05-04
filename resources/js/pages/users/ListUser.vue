<script setup>
import { ref, onMounted, reactive } from 'vue';
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';

const users = ref([]);

const getUsers = () => {
    axios.get('/api/users')
        .then((response) => {
            users.value = response.data;
        })
}


const schema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8)
});

const createUser = (values, { resetForm }) => {
    axios.post('/api/users', values)
        .then((response) => {
            $('#createUserModal').modal('hide');
            resetForm();
            users.value.unshift(response.data);
            getUsers();
        })
        .catch((error) => {
            console.log(error);
        })
}

onMounted(() => {
    getUsers();
});

</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">List Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">List Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createUserModal">
                Add User
            </button>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.role }}</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" id="createUserModal" tabindex="-1"
        aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form @submit="createUser" :validation-schema="schema" v-slot="{ errors }">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field type="text" class="form-control" id="name" name="name" :class="{ 'is-invalid': errors.name }"
                                aria-describedby="name" placeholder="Enter Full Name" />
                                <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field type="email" class="form-control" id="email" name="email" :class="{ 'is-invalid': errors.email }"
                                aria-describedby="email" placeholder="Enter Email" />
                                <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <Field type="password" class="form-control" id="password" name="password" :class="{ 'is-invalid': errors.password }"
                                aria-describedby="password" placeholder="Enter Password" />
                                <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </Form>
            </div>
    </div>
    </div>
</template>
<script setup>
import { ref, onMounted, reactive, watch } from 'vue';
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../../js/toastr';
import UserListItem from './UserListItem.vue';
import axios from 'axios';
import { debounce } from 'lodash';

const users = ref([]);
const editing = ref(false);
const formValues = ref({
    name: '',
    email: '',
    password: ''
});
const form = ref(null);
const toastr = useToastr();
const searchQuery = ref(null);

const getUsers = () => {
    axios.get('/api/users')
        .then((response) => {
            users.value = response.data;
        })
}

const createUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8)
});

const editUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().min(8)
});

const addUser = () => {
    editing.value = false;
    form.value.resetForm();
    $('#userFormModal').modal('show');
}

const createUser = (values, { resetForm, setErrors }) => {
    axios.post('/api/users', values)
        .then((response) => {
            $('#userFormModal').modal('hide');
            form.value.resetForm();
            users.value.unshift(response.data);
            getUsers();
            toastr.success('User created successfully!');
        })
        .catch((error) => {
            if(error.response.data.errors) {
                setErrors(error.response.data.errors);
            }
        })
}

const editUser = (user) => {
    editing.value = true;
    $('#userFormModal').modal('show');
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email,
    };
};


const updateUser = (values, { resetForm, setErrors }) => {
    axios.put('/api/users/' + formValues.value.id, values)
        .then((response) => {
            $('#userFormModal').modal('hide');
            const index = users.value.findIndex((user) => user.id === response.data.id);
            users.value[index] = response.data;
            editing.value = false;
            form.value.resetForm();
            getUsers();
            toastr.success('User updated successfully!');
        })
        .catch((error) => {
            if(error.response.data.errors) {
                setErrors(error.response.data.errors);
            }
        })
}

const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateUser(values, actions, { resetForm: true });
    } else {
        createUser(values, actions, { resetForm: true });
    }
}

const userDeleted = (userId) => {
    users.value = users.value.filter(user => user.id !== userId);
}

const search = () => {
    axios.get('/api/users/search', {
        params: {
            query: searchQuery.value
        }
    })
    .then(response => {
        users.value = response.data;
    })
    .catch(error => {
        console.log(error);
    })
}

watch(searchQuery, debounce(() => {
    search();
},1000));

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
            <div class="d-flex justify-content-between">
                <button @click="addUser" type="button" class="btn btn-primary mb-3">
                    Add User
                </button>
                <div>
                    <input type="text" class="form-control" v-model="searchQuery" placeholder="Search..." autofocus>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="users.length > 0">
                            <UserListItem v-for="(user, index) in users"
                                    :key="user.id"
                                    :user=user
                                    :index=index
                                    @edit-user="editUser"
                                    @user-deleted="userDeleted"
                            />
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No users found...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" id="userFormModal" tabindex="-1" aria-labelledby="userFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userFormModalLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control" :class="{ 'is-invalid': errors.name }"
                                id="name" aria-describedby="nameHelp" placeholder="Enter full name"
                                v-model="formValues.name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control"
                                :class="{ 'is-invalid': errors.email }" id="email" aria-describedby="emailHelp"
                                placeholder="Enter email" v-model="formValues.email" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <Field name="password" type="password" class="form-control"
                                :class="{ 'is-invalid': errors.password }" id="password" placeholder="Enter password"
                                v-model="formValues.password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            {{ editing ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
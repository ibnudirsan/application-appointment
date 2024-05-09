<script setup>

import { formatDate } from '../../helper.js';
import { useToastr } from '../../../js/toastr';
import { ref } from 'vue';

const toastr = useToastr();

defineProps({
    user: Object,
    index: Number
});

const emit = defineEmits(['userDeleted','editUser']);
const userIdBeingDelete = ref(null);

const confirmUserDelete = (user) => {
    userIdBeingDelete.value = user.id;
    $('#deleteUserModal').modal('show');
}

const deleteUser = () => {
    axios.delete('/api/users/' + userIdBeingDelete.value)
        .then((response) => {
            $('#deleteUserModal').modal('hide');
            toastr.success('User deleted successfully!');
            emit('userDeleted', userIdBeingDelete.value);
        })
        .catch((error) => {
            console.log(error);
        })
}


const roles = ref([
    {
        name: 'ADMIN',
        value: 1,
    },
    {
        name: 'USER',
        value: 2,
    }
]);

const changeRole = (user, role) => {
    axios.patch(`/api/users/${user.id}/change-role`, {
        role: role
    })
    .then(()=>{
        toastr.success('Role changed successfully!');
    })
    .catch((error) => {        
        console.log(error);
    })
}

</script>

<template>
    <tr>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="role in roles" :value="role.value" :key="role.value" :selected="user.role === role.name">{{ role.name }}</option>
            </select>
        </td>
        <td>{{ formatDate(user.created_at) }}</td>
        <td>
            <a href="#" class="btn btn-primary btn-sm mr-2" @click="$emit('editUser', user)">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" class="btn btn-danger btn-sm ml-2" @click="confirmUserDelete(user)">
                <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>

    <div class="modal fade" data-backdrop="static" id="deleteUserModal" tabindex="-1"
        aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">
                        <span>
                            Delete User
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button @click.prevent="deleteUser" type="submit" class="btn btn-danger">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
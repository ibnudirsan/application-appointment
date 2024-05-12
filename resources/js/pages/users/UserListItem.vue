<script setup>

import { formatDate } from '../../helper.js';
import { useToastr } from '../../../js/toastr';
import { ref } from 'vue';

const toastr = useToastr();

const props = defineProps({
    user: Object,
    index: Number,
    selectAll: Boolean,
});

const emit = defineEmits(['userDeleted','editUser','confirmUserDelete']);

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

const toggleSelection = () => {
    emit('toggleSelection', props.user);
}
</script>

<template>
    <tr>
        <td>
            <input type="checkbox" :checked="selectAll" @change="toggleSelection">
        </td>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="role in roles" :value="role.value" :key="role.value" :selected="user.role === role.name">{{ role.name }}</option>
            </select>
        </td>
        <td>{{ user.formatted_created_at }}</td>
        <td>
            <a href="#" class="btn btn-primary btn-sm mr-2" @click="$emit('editUser', user)">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" class="btn btn-danger btn-sm ml-2" @click="$emit('confirmUserDeletion', user.id)">
                <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>
</template>
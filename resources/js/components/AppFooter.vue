<script setup>
defineProps({
    setting: Object
})
</script>
<template>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>
        <strong>Copyright &copy; 2024-2021 <a href="#">{{ setting?.app_name }}</a>.</strong> All
        rights reserved.
    </footer>
</template>
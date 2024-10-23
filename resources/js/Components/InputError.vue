<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <small class="text-sm text-red-600 text-danger">
            {{ message }}
        </small>
    </div>
</template>

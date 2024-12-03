<!-- resources/js/Components/Toast.vue -->
<template>
    <div></div>
</template>

<script setup>
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import { watch } from "vue";

const props = defineProps({
    successMessage: {
        type: Object,
        default: () => ({}),
    },
    errorMessage: {
        type: Object,
        default: () => ({}),
    },
});

const showToast = (type, message) => {
    toast[type](message.message, {
        autoClose: 5000,
    });
};

watch(
    () => props.successMessage,
    (newVal) => {
        if (newVal && newVal.id) {
            showToast("success", newVal);
        }
    },
    { immediate: true }
);

watch(
    () => props.errorMessage,
    (newVal) => {
        if (newVal && newVal.id) {
            showToast("error", newVal);
        }
    },
    { immediate: true }
);
</script>

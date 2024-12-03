<template>
    <button
        data-bs-toggle="modal"
        :data-bs-target="target"
        class="toggle btn btn-icon btn-dark text-white hover:!bg-[#c61f25] hover:!border-[#c61f25]"
    >
        <em class="icon ni ni-plus"></em>
    </button>
</template>

<script setup>
defineProps({
    target: {
        type: String,
        required: true,
    },
});
</script>

<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
    modalId: {
        type: String,
        required: true,
    },
    defaultValues: {
        type: Object,
        default: {},
    },
});

const form = useForm({});

const submitForm = () => {
    form.put(props.url, {
        onSuccess: () => {
            $(`#${props.modalId}`).modal("hide");
        },
        onError: () => {
            $(`#${props.modalId}`).modal("hide");
            console.log("Error");
        },
    });
};

const patrolLocationDisplay = computed(() => {
    if (
        props.defaultValues.patrol_role.id == 1 ||
        props.defaultValues.patrol_role.id == 4
    ) {
        return "All";
    }
    return props.defaultValues?.patrol_location?.name ?? "N/A";
});
</script>

<template>
    <Modal :title="title" :modalId="modalId">
        <div
            v-if="Object.keys(defaultValues).length > 0"
            class="mt-[-10px] flex flex-col gap-x-2 mb-2"
        >
            <div class="flex gap-x-2">
                <strong>
                    {{ defaultValues.name }}
                </strong>
                <span class="badge rounded-pill bg-dark">{{
                    defaultValues.patrol_role.name
                }}</span>
            </div>

            <span>
                {{ defaultValues?.company?.name ?? "N/A" }} ({{
                    patrolLocationDisplay
                }})
            </span>
        </div>
        <p class="mb-0">Are you sure want to do this action?</p>
        <form @submit.prevent="submitForm">
            <button
                class="btn btn-dark px-4 ms-auto mt-4 d-flex"
                style="width: fit-content"
                :disabled="form.processing"
            >
                {{ form.processing ? "Resetting..." : "Reset" }}
            </button>
        </form>
    </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted, watch } from "vue";

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

const form = useForm({
    status: props.defaultValues.is_active || 0,
});

// Watch for changes in defaultValues and set form data
watch(
    () => props.defaultValues,
    (newValues) => {
        if (newValues) {
            form.status = newValues.is_active;
        }
    },
    { deep: true }
);

onMounted(() => {
    if (props.defaultValues) {
        form.status = props.defaultValues.is_active;
    }
});

const submitForm = () => {
    form.put(props.url, {
        onSuccess: () => {
            form.reset();
            $(`#${props.modalId}`).modal("hide");
        },
        onError: () => {
            $(`#${props.modalId}`).modal("hide");
            console.log("Error");
        },
    });
};
</script>

<template>
    <Modal :title="title" :modalId="modalId">
        <p class="mb-0">Are you sure want to do this action?</p>
        <code class="text-danger">* Field required</code>
        <form @submit.prevent="submitForm">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="status"
                            >Status<code class="ms-1">*</code></label
                        >
                        <select
                            class="form-select"
                            name="status"
                            v-bind="form"
                            v-model="form.status"
                        >
                            <option value="1" :selected="form.status == 1">
                                Active
                            </option>
                            <option value="0" :selected="form.status == 0">
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <button
                type="submit"
                class="btn btn-dark px-4 ms-auto mt-4 d-flex"
                style="width: fit-content"
                :disabled="form.processing"
            >
                {{ form.processing ? "Saving..." : "Save" }}
            </button>
        </form>
    </Modal>
</template>

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

onMounted(() => {
    if (Object.keys(props.defaultValues).length > 0) {
        form.mode = props.defaultValues.mode;
    }
});

const form = useForm({
    mode: props.defaultValues.mode,
});

// Watch for changes in defaultValues and set form data
watch(
    () => props.defaultValues,
    (newValues) => {
        if (newValues) {
            form.mode = newValues.mode == null ? 0 : newValues.mode;
        }
    }
);

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
        <div
            v-if="Object.keys(defaultValues).length > 0"
            class="mt-[-10px] flex gap-x-2 mb-2"
        >
            <strong>
                {{ defaultValues.name }}
            </strong>
            |
            <span>
                {{ defaultValues?.company?.name ?? "N/A" }} ({{
                    defaultValues?.patrol_location?.name ?? "N/A"
                }})
            </span>
        </div>
        <p class="mb-0">Are you sure want to do this action?</p>
        <code class="text-danger">* Field required</code>
        <form @submit.prevent="submitForm">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="model"
                            >Photo Mode<code class="ms-1">*</code></label
                        >
                        <select
                            class="form-select"
                            name="model"
                            v-bind="form"
                            v-model="form.mode"
                        >
                            <option value="1" :selected="form.mode == 1">
                                Realtime
                            </option>
                            <option value="0" :selected="form.mode == null">
                                Gallery
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

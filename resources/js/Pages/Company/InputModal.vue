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
    isEdit: {
        type: Boolean,
        default: false,
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
    name: props.defaultValues.name || "",
    image: null,
});

// Watch for changes in defaultValues and set form data
watch(
    () => props.defaultValues,
    (newValues) => {
        if (newValues) {
            form.name = newValues.name || "";
            form.image = null;
        }
    }
);

onMounted(() => {
    if (Object.keys(props.defaultValues).length) {
        form.name = props.defaultValues.name;
        form.image = null;
    }
});

function handleImageUpload(event) {
    form.image = event.target.files[0];
}

function handleSubmit() {
    const method = props.isEdit ? "put" : "post";
    form[method](props.url, {
        onSuccess: () => {
            form.reset();
            $(`#${props.modalId}`).modal("hide");
        },
        onError: () => {
            console.log("Error");
        },
    });
}
</script>

<template>
    <Modal :title="title" :modalId="modalId">
        <p class="mb-0">Add information for the company.</p>
        <code class="text-danger">* Field required</code>
        <form @submit.prevent="handleSubmit">
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group mt-2">
                        <label class="form-label" for="companyName"
                            >Name<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.name"
                                name="name"
                                id="companyName"
                                placeholder="Enter company name"
                                :maxlength="255"
                                :minlength="3"
                                autocomplete="off"
                                required
                            />
                        </div>
                    </div>
                </div>
                <div class="col-12" v-if="!props.isEdit">
                    <div class="form-group">
                        <label class="form-label" for="companyImage"
                            >Image<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <input
                                type="file"
                                accept="image/jpeg, image/png, image/jpg"
                                class="form-control"
                                @change="handleImageUpload"
                                id="companyImage"
                                name="image"
                                required
                            />
                        </div>
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

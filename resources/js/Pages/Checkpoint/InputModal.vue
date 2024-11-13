<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";

const props = defineProps({
    companies: {
        type: Array,
        required: true,
    },
    locations: {
        type: Array,
        required: true,
    },
    modalId: {
        type: String,
        required: true,
    },
    modalData: {
        type: Object,
        required: true,
        default: () => ({}),
        validator: (value) => {
            return (
                typeof value.title === "string" &&
                typeof value.url === "string" &&
                typeof value.isEdit === "boolean" &&
                typeof value.defaultValues === "object"
            );
        },
    },
});

const form = useForm({
    company_id: props.modalData.defaultValues.company_id || "",
    patrol_location_id: props.modalData.defaultValues.location_id || "",
    name: props.modalData.defaultValues.name || "",
});

const filteredLocations = ref([]);

// Watch for `companyId` changes and filter `locations`
watch(
    () => form.company_id,
    (newCompanyId) => {
        filteredLocations.value = props.locations.filter(
            (location) => location.company_id == newCompanyId
        );
        form.patrol_location_id = ""; // Reset locationId when company changes
    }
);

// Watch for changes in `defaultValues` to set initial form data
watch(
    () => props.modalData.defaultValues,
    (newValues) => {
        if (newValues) {
            form.company_id = newValues.company_id || "";
            form.patrol_location_id = newValues.location_id || "";
            form.name = newValues.name || "";
        }
    },
    { deep: true }
);

onMounted(() => {
    if (props.modalData.defaultValues) {
        form.company_id = props.modalData.defaultValues.company_id;
        form.patrol_location_id = props.modalData.defaultValues.location_id;
        form.name = props.modalData.defaultValues.name;
    }
    // Initialize filtered locations based on the current companyId
    filteredLocations.value = props.locations.filter(
        (location) => location.company_id == form.company_id
    );
});

const submitForm = () => {
    const method = props.modalData.isEdit ? "put" : "post";
    form[method](props.modalData.url, {
        onSuccess: () => {
            form.reset();
            $(`#${props.modalId}`).modal("hide");
        },
        onError: () => {
            console.log("Error");
        },
    });
};
</script>

<template>
    <Modal :title="modalData.title" :modalId="modalId">
        <p class="mb-0">Add information for the checkpoint.</p>
        <code class="text-danger">* Field required</code>
        <form @submit.prevent="submitForm">
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group mt-3">
                        <label class="form-label" for="company_id"
                            >Company<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <select
                                @change="handleCompanyChange"
                                v-model="form.company_id"
                                class="form-select"
                                required
                            >
                                <option value="" disabled>
                                    Choose Company
                                </option>
                                <option
                                    v-for="company in companies"
                                    :key="company.id"
                                    :value="company.id"
                                >
                                    {{ company.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="location_id"
                            >Location<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <select
                                v-model="form.patrol_location_id"
                                class="form-select"
                                required
                                :disabled="!form.company_id"
                            >
                                <option value="" disabled>
                                    Choose Location
                                </option>
                                <option
                                    v-for="location in filteredLocations"
                                    :key="location.id"
                                    :value="location.id"
                                >
                                    {{ location.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="name"
                            >Name<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <input
                                type="text"
                                class="form-control"
                                v-bind="form"
                                v-model="form.name"
                                name="name"
                                placeholder="Enter checkpoint name"
                                :maxlength="255"
                                :minlength="3"
                                autocomplete="off"
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

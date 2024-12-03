<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    companies: {
        type: Array,
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

const error = ref(null);

const form = useForm({
    company_id: props.defaultValues.company_id || "",
    name: props.defaultValues.name || "",
    address: props.defaultValues.address || "",
    phone: props.defaultValues.phone || "",
});

const sanitizeInput = ({ target }) => {
    let sanitizedInput = target.value.replace(/[^0-9]/g, "").slice(0, 13);

    // Set form's phone value directly to prevent issues
    form.phone = sanitizedInput;

    if (sanitizedInput.length < 10 && sanitizedInput.length > 0) {
        error.value = "Phone number must be at least 10 digits";
    } else if (sanitizedInput.length > 13) {
        error.value = "Phone number must be at most 13 digits";
    } else {
        error.value = null;
    }
};

watch(
    () => props.defaultValues,
    (newValues) => {
        if (newValues) {
            form.company_id = newValues.company_id || "";
            form.name = newValues.name || "";
            form.address = newValues.address || "";
            form.phone = newValues.phone
                ? newValues.phone.replace(/^(\+62)+/, "0")
                : "";
        }
    },
    { deep: true }
);

onMounted(() => {
    if (Object.keys(props.defaultValues).length > 0) {
        form.company_id = props.defaultValues.company_id;
        form.name = props.defaultValues.name;
        form.address = props.defaultValues.address;
        form.phone = props.defaultValues.phone
            ? props.defaultValues.phone.replace(/^(\+62)+/, "0")
            : "";
    }
});

const handleSubmit = () => {
    // const method = props.isEdit ? "put" : "post";
    const method = "post";
    form[method](props.url, {
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
    <!-- Modal -->
    <Modal :title="title" :modalId="modalId">
        <p v-if="isEdit" class="mb-0">Add information for the location.</p>
        <code v-if="isEdit" class="text-danger">* Field required</code>
        <form @submit.prevent="handleSubmit">
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group" :class="isEdit ? 'mt-3' : ''">
                        <label class="form-label" for="companyId"
                            >Company<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <select
                                id="companyId"
                                v-model="form.company_id"
                                class="form-select"
                                :disabled="!props.isEdit"
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
                        <label class="form-label" for="LocationName"
                            >Name<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                id="LocationName"
                                v-model="form.name"
                                placeholder="Enter location name"
                                :maxlength="255"
                                :minlength="3"
                                required
                                :readonly="!props.isEdit"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="locationAddress"
                            >Address<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <textarea
                                type="text"
                                class="form-control"
                                name="address"
                                id="locationAddress"
                                placeholder="Enter address"
                                rows="2"
                                :maxlength="255"
                                :minlength="5"
                                v-model="form.address"
                                required
                                :readonly="!props.isEdit"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="phone"
                            >Mobile Phone<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-left">
                                <em class="icon ni ni-call"></em>
                            </div>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="form-control"
                                autocomplete="off"
                                name="phone"
                                id="phone"
                                placeholder="08123456789"
                                @input="sanitizeInput"
                                :readonly="!props.isEdit"
                            />
                        </div>
                        <span v-if="error">
                            <small class="text-danger fst-italic">{{
                                error
                            }}</small>
                        </span>
                    </div>
                </div>
            </div>
            <button
                v-if="props.isEdit"
                :disabled="form.processing"
                type="submit"
                class="btn btn-dark px-4 ms-auto mt-4 d-flex"
                style="width: fit-content"
            >
                {{ form.processing ? "Loading..." : "Save" }}
            </button>
        </form>
    </Modal>
</template>

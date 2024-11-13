<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";

const props = defineProps({
    companies: {
        type: Array,
        required: true,
        default: () => [],
    },
    locations: {
        type: Array,
        required: true,
        default: () => [],
    },
    roles: {
        type: Array,
        required: true,
        default: () => [],
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
    email: props.modalData.defaultValues.email || "",
    mobile_no: props.modalData.defaultValues.mobile_no || "",
    patrol_role_id: props.modalData.defaultValues.patrol_role_id || "",
    password: props.modalData.defaultValues.password || "",
});

// Create a reactive state for filtered locations
const filteredLocations = ref([]);

// Watch for `companyId` changes and filter `locations`
watch(
    () => form.company_id,
    (newCompanyId) => {
        filteredLocations.value = props.locations.filter(
            (location) => location.company_id == newCompanyId
        );
        form.patrol_location_id = "";
    }
);

onMounted(() => {
    filteredLocations.value = props.locations.filter(
        (location) => location.company_id == form.company_id
    );
});

const showPassword = ref(false);

const locationRequired = ref(true);

const error = ref(null);

const sanitizeInput = ({ target }) => {
    let sanitizedInput = target.value.replace(/[^0-9]/g, "").slice(0, 13);

    // Set form's phone value directly to prevent issues
    form.mobile_no = sanitizedInput;

    if (sanitizedInput.length < 10 && sanitizedInput.length > 0) {
        error.value = "Phone number must be at least 10 digits";
    } else if (sanitizedInput.length > 13) {
        error.value = "Phone number must be at most 13 digits";
    } else {
        error.value = null;
    }
};

const handleRoleChange = () => {
    locationRequired.value = true;
    if (form.patrol_role_id == 1 || form.patrol_role_id == 4) {
        locationRequired.value = false;
    }
};

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

const patrolLocationDisplay = computed(() => {
    if (
        props.modalData.defaultValues.patrol_role.id == 1 ||
        props.modalData.defaultValues.patrol_role.id == 4
    ) {
        return "All";
    }
    return props.modalData.defaultValues?.patrol_location?.name ?? "N/A";
});
</script>

<template>
    <Modal :title="modalData.title" :modalId="modalId">
        <div
            v-if="
                Object.keys(modalData.defaultValues).length > 0 &&
                modalData.isEdit
            "
            class="mt-[-10px] flex flex-col gap-x-2 mb-2"
        >
            <div class="flex gap-x-2">
                <strong>
                    {{ modalData.defaultValues.name }}
                </strong>
                <span class="badge rounded-pill bg-dark">{{
                    modalData.defaultValues.patrol_role.name
                }}</span>
            </div>

            <span>
                {{ modalData.defaultValues?.company?.name ?? "N/A" }} ({{
                    patrolLocationDisplay
                }})
            </span>
        </div>
        <p class="mb-0">Add information for the guard.</p>
        <code class="text-danger">* Field required</code>
        <form @submit.prevent="submitForm">
            <div class="row g-3">
                <template v-if="!modalData?.isEdit">
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
                                    :required="locationRequired"
                                    :disabled="
                                        !form.company_id || !locationRequired
                                    "
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
                                    placeholder="Enter guard name"
                                    :maxlength="255"
                                    :minlength="3"
                                    autocomplete="off"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="email"
                                >Email<code class="ms-1">*</code></label
                            >
                            <div class="form-control-wrap">
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="form.email"
                                    name="email"
                                    placeholder="your-email@mail"
                                    :maxlength="255"
                                    :minlength="3"
                                    autocomplete="off"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="mobile_no"
                                >Mobile Phone<code class="ms-1">*</code></label
                            >
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-call"></em>
                                </div>
                                <input
                                    v-model="form.mobile_no"
                                    type="text"
                                    class="form-control"
                                    autocomplete="off"
                                    name="mobile_no"
                                    placeholder="08123456789"
                                    @input="sanitizeInput"
                                    required
                                />
                            </div>
                            <span v-if="error">
                                <small class="text-danger fst-italic">{{
                                    error
                                }}</small>
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="patrol_role_id"
                                >Role<code class="ms-1">*</code></label
                            >
                            <div class="form-control-wrap">
                                <select
                                    v-model="form.patrol_role_id"
                                    class="form-select"
                                    @change="handleRoleChange"
                                    required
                                >
                                    <option value="" disabled>
                                        Choose Role
                                    </option>
                                    <option
                                        v-for="role in roles"
                                        :key="role.id"
                                        :value="role.id"
                                    >
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="col-12">
                    <div
                        class="form-group"
                        :class="modalData?.isEdit ? 'mt-3' : ''"
                    >
                        <label class="form-label" for="password">
                            {{ modalData.isEdit ? "New " : "" }}
                            Password<code class="ms-1">*</code></label
                        >
                        <div class="form-control-wrap">
                            <a
                                @click="showPassword = !showPassword"
                                tabindex="-1"
                                href="#"
                                class="form-icon form-icon-right passcode-switch"
                                data-target="passwords"
                            >
                                <em
                                    class="passcode-icon icon-show icon ni"
                                    :class="{
                                        'ni-eye': !showPassword,
                                        'ni-eye-off': showPassword,
                                    }"
                                ></em>
                            </a>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                class="form-control"
                                v-model="form.password"
                                :minlength="8"
                                name="password"
                                placeholder="********"
                                autocomplete="off"
                                required
                            />
                            <small class="text-black">Min. 8 characters</small>
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

<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";

const props = defineProps({
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
    modalId: {
        type: String,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: props.modalData.defaultValues.name,
    email: props.modalData.defaultValues.email,
    role_id: props.modalData.defaultValues.role_id,
    password: props.modalData.defaultValues.password,
});

const showPassword = ref(false);

// Watch for changes in defaultValues and set form data
watch(
    () => props.modalData.defaultValues,
    (newValues) => {
        if (newValues) {
            form.name = newValues.name || "";
            form.email = newValues.email || "";
            form.role_id = newValues.role_id || "";
            form.password = newValues.password || "";
        }
    },
    { deep: true }
);

onMounted(() => {
    if (props.modalData.defaultValues) {
        form.name = props.modalData.defaultValues.name;
        form.email = props.modalData.defaultValues.email;
        form.role_id = props.modalData.defaultValues.role_id;
        form.password = props.modalData.defaultValues.password;
    }
});

const submitForm = () => {
    const method = props.modalData.isEdit ? "put" : "post";
    form[method](props.modalData.url, {
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
                    modalData.defaultValues.role.name
                }}</span>
            </div>

            <span>
                {{ modalData.defaultValues.email }}
            </span>
        </div>
        <p class="mb-0">Add information for the user.</p>
        <code class="text-danger">* Field required</code>
        <form @submit.prevent="submitForm">
            <div class="row g-3">
                <template v-if="!modalData?.isEdit">
                    <div class="col-12">
                        <div class="form-group mt-3">
                            <label class="form-label" for="name"
                                >Name<code class="ms-1">*</code></label
                            >
                            <div class="form-control-wrap">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    name="name"
                                    id="name"
                                    placeholder="Enter user's name"
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
                            <label class="form-label" for="email">
                                Email
                                <code class="ms-1">*</code>
                            </label>
                            <div class="form-control-wrap">
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="form.email"
                                    id="email"
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
                            <label class="form-label" for="roleId"
                                >Role<code class="ms-1">*</code></label
                            >
                            <div class="form-control-wrap">
                                <select
                                    name="role_id"
                                    id="roleId"
                                    v-model="form.role_id"
                                    class="form-select"
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
                                id="password"
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
                class="btn btn-dark px-4 ms-auto mt-4 d-flex"
                style="width: fit-content"
                :disabled="form.processing"
            >
                {{ form.processing ? "Saving..." : "Save" }}
            </button>
        </form>
    </Modal>
</template>

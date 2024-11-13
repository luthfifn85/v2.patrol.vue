<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import { QrcodeSvg } from "qrcode.vue";

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
        form.uuid = props.defaultValues.uuid;
    }
    "Initial props.defaultValues:", props.defaultValues;
});

let reactiveValues = ref({ ...props.defaultValues });

const form = useForm({
    uuid: reactiveValues.uuid,
});

watch(
    () => props.defaultValues,
    (newValues) => {
        if (newValues) {
            Object.keys(newValues).forEach((key) => {
                reactiveValues.value[key] = newValues[key];
            });
            form.uuid = newValues.uuid;
            "After updating reactiveValues in watch:", reactiveValues.value;
        }
    },
    { deep: true }
);

const handleGenerateQR = () => {
    form.put(props.url, {
        onSuccess: (response) => {
            "Success", response;
            const updatedCheckpoint = response.props?.checkpoints?.find(
                (c) => c.id === props.defaultValues.id
            );

            if (updatedCheckpoint) {
                reactiveValues.value.uuid = updatedCheckpoint.uuid;
                form.uuid = updatedCheckpoint.uuid;
                "After updating reactiveValues:", reactiveValues.value;
            }
        },
        onError: () => {
            ("Error");
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
        <div class="row mt-2">
            <div class="col-12">
                <div class="mt-3 text-center">
                    <QrcodeSvg
                        :size="180"
                        class="mx-auto"
                        :imageSettings="{
                            src: 'images/logo-abs.png',
                            height: 35,
                            width: 35,
                            excavate: true,
                        }"
                        :value="reactiveValues.uuid"
                        level="H"
                    />
                    <p class="mt-3">{{ reactiveValues.uuid }}</p>
                </div>
            </div>
        </div>
        <button
            type="submit"
            class="btn btn-dark px-4 ms-auto mt-4 d-flex"
            style="width: fit-content"
            @click="handleGenerateQR"
            :disabled="form.processing"
        >
            {{ form.processing ? "Generating..." : "Generate New QR" }}
        </button>
    </Modal>
</template>

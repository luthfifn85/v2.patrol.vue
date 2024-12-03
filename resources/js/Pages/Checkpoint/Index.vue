<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, ref } from "vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import InputModal from "./InputModal.vue";
import StatusModal from "@/Pages/Checkpoint/StatusModal.vue";
import QRCodeModal from "@/Pages/Checkpoint/QRCodeModal.vue";
import createDtOptions from "@/datatables-config";
import jszip from "jszip";
import pdfmake from "pdfmake";

DataTable.use(DataTablesCore);
DataTablesCore.Buttons.jszip(jszip);
DataTablesCore.Buttons.pdfMake(pdfmake);

const DtOptions = createDtOptions();

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    checkpoints: {
        type: Array,
        required: true,
    },
    checkpointCount: {
        type: Number,
        required: true,
    },
    companies: {
        type: Array,
        required: true,
    },
    locations: {
        type: Array,
        required: true,
    },
});

const columns = [
    {
        data: "name",
        className: "align-content-center",
        render: (data) => {
            return `<strong>${data}</strong>`;
        },
    },
    {
        data: "patrol_location.name",
        className: "align-content-center",
    },
    {
        data: "company.name",
        className: "align-content-center",
    },
    {
        data: "is_active",
        className: "align-content-center text-center",
        render: (data) => {
            return data
                ? '<span class="badge rounded-pill bg-success">Active</span>'
                : '<span class="badge rounded-pill bg-danger">Inactive</span>';
        },
    },
    {
        data: null,
        className: "align-content-center justify-items-start !z-[50]",
        defaultContent: "",
    },
];

let modalData = ref({
    title: "",
    url: "",
    isEdit: false,
    defaultValues: {},
});

const openModal = (
    modalTarget,
    isEdit,
    url,
    defaultValues = {},
    title = "Location"
) => {
    modalData.value = reactive({
        ...modalData.value,
        isEdit,
        url,
        title,
        defaultValues,
    });

    $(modalTarget).modal("show");
};
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm mt-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">
                        {{ title }}
                    </h3>
                    <div class="nk-block-des text-soft">
                        <p>
                            You have total
                            <span class="text-base">{{ checkpointCount }}</span>
                            data.
                        </p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a
                            href="#"
                            class="btn btn-icon btn-trigger toggle-expand me-n1"
                            data-target="pageMenu"
                        >
                            <em class="icon ni ni-more-v"></em>
                        </a>
                        <div
                            class="toggle-expand-content"
                            data-content="pageMenu"
                        >
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <div class="dropdown">
                                        <button
                                            data-bs-toggle="modal"
                                            @click="
                                                openModal(
                                                    '#createCheckpointModal',
                                                    true,
                                                    route('locations.store'),
                                                    undefined,
                                                    'New Location'
                                                )
                                            "
                                            class="toggle btn btn-icon btn-dark"
                                        >
                                            <em class="icon ni ni-plus"></em>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <DataTable
                    :options="DtOptions"
                    :data="checkpoints"
                    :columns="columns"
                    class="nowrap table datatable-wrap table-responsive"
                >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Company</th>
                            <th style="width: 15%">Status</th>
                            <th
                                class="nk-tb-col nk-tb-col-tools text-end"
                                style="width: 10%"
                            ></th>
                        </tr>
                    </thead>
                    <template #column-4="{ rowData }">
                        <div class="dropdown">
                            <a
                                class="btn btn-icon text-sm"
                                type="button"
                                data-bs-toggle="dropdown"
                            >
                                <em class="icon ni ni-more-v"></em>
                            </a>
                            <div class="dropdown-menu">
                                <ul class="link-list-opt no-bdr">
                                    <li
                                        @click="
                                            openModal(
                                                '#qrCodeModal',
                                                true,
                                                route(
                                                    'checkpoints.generate_qr_code',
                                                    rowData.id
                                                ),
                                                rowData,
                                                'Generate QR Code'
                                            )
                                        "
                                    >
                                        <a href="#">
                                            <em class="icon ni ni-qr"></em>
                                            <span>View QR Code</span>
                                        </a>
                                    </li>
                                    <li
                                        @click="
                                            openModal(
                                                '#statusCheckpointModal',
                                                true,
                                                route(
                                                    'checkpoints.update',
                                                    rowData.id
                                                ),
                                                rowData,
                                                'Change Photo Mode'
                                            )
                                        "
                                    >
                                        <a href="#">
                                            <em class="icon ni ni-camera"></em>
                                            <span>Change Mode</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>

        <InputModal
            :companies="companies"
            :locations="locations"
            :modalId="'createCheckpointModal'"
            :modalData="modalData"
        />

        <StatusModal
            :title="modalData.title"
            :url="modalData.url"
            :modalId="'statusCheckpointModal'"
            :defaultValues="modalData.defaultValues"
        />

        <QRCodeModal
            :title="modalData.title"
            :url="modalData.url"
            :modalId="'qrCodeModal'"
            :defaultValues="modalData.defaultValues"
        />
    </AppLayout>
</template>

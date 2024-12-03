<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, ref } from "vue";
import ModalButton from "@/Components/ModalButton.vue";
import InputModal from "./InputModal.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
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
    locations: {
        type: Array,
        required: true,
    },
    locationCount: {
        type: Number,
        required: true,
    },
    companies: {
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
        data: "company.name",
        className: "align-content-center",
    },
    {
        data: "checkpoints_count",
        className: "align-content-center",
        render: (data) => {
            return `<em class="icon ni ni-map-pin me-1"></em> ${data}`;
        },
    },
    {
        data: "officers_count",
        className: "align-content-center",
        render: (data) => {
            return `<em class="icon ni ni-map-pin me-1"></em> ${data}`;
        },
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
                            <span class="text-base">{{ locationCount }}</span>
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
                                        <ModalButton
                                            :target="'#createModal'"
                                            @click="
                                                openModal(
                                                    '#createModal',
                                                    true,
                                                    route('locations.store'),
                                                    undefined,
                                                    'New Location'
                                                )
                                            "
                                        />
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
                    :data="locations"
                    :columns="columns"
                    class="nowrap table datatable-wrap table-responsive"
                >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Checkpoint</th>
                            <th>Guard</th>
                            <th style="width: 15%">Status</th>
                            <th
                                class="nk-tb-col nk-tb-col-tools text-end"
                                style="width: 10%"
                            ></th>
                        </tr>
                    </thead>
                    <template #column-5="{ rowData }">
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
                                                '#createModal',
                                                false,
                                                '',
                                                rowData,
                                                'Location Details'
                                            )
                                        "
                                        class="hover:text-blue-400"
                                    >
                                        <a href="#">
                                            <em class="icon ni ni-info"></em>
                                            <span>View Details</span>
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
            :modalId="'createModal'"
            :title="modalData.title"
            :url="modalData.url"
            :isEdit="modalData.isEdit"
            :defaultValues="modalData.defaultValues"
        />
    </AppLayout>
</template>

<script setup>
import ModalButton from "@/Components/ModalButton.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import { reactive, ref } from "vue";
import InputModal from "@/Pages/Company/InputModal.vue";
import StatusModal from "@/Pages/Company/StatusModal.vue";
import createDtOptions from "@/datatables-config";
import jszip from "jszip";
import pdfmake from "pdfmake";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    companies: {
        type: Array,
        required: true,
    },
});

DataTable.use(DataTablesCore);
DataTablesCore.Buttons.jszip(jszip);
DataTablesCore.Buttons.pdfMake(pdfmake);

const DtOptions = createDtOptions({
    fixedColumns: false,
    scrollX: false,
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
        // data: "location",
        data: "patrol_locations_count",
        className: "align-content-center",
        render: (data) => {
            return `<em class="icon ni ni-location me-1"></em> ${data}`;
        },
    },
    {
        data: "checkpoints_count",
        className: "align-content-center",
        render: (data) => {
            return `<em class="icon ni ni-map-pin me-1"></em> ${data}`;
        },
    },
    {
        data: "is_active",
        className: "align-content-center",
        render: (data) => {
            return data
                ? '<span class="badge rounded-pill bg-success">Active</span>'
                : '<span class="badge rounded-pill bg-danger">Inactive</span>';
        },
    },
    {
        data: null,
        className: "align-content-center justify-items-center",
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
    title = "Company"
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
                    <h3 class="nk-block-title page-title mb-0">{{ title }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>
                            You have total
                            <span class="text-base">{{
                                companies.length
                            }}</span>
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
                            ><em class="icon ni ni-menu-alt-r"></em
                        ></a>
                        <div
                            class="toggle-expand-content"
                            data-content="pageMenu"
                        >
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <ModalButton
                                        :target="'createModal'"
                                        @click="
                                            openModal(
                                                '#createCompanyModal',
                                                false,
                                                route('companies.store'),
                                                undefined,
                                                'Add New Company'
                                            )
                                        "
                                    />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- datatable -->
        <div class="nk-block">
            <div class="card card-bordered card-preview">
                <div class="card-inner">
                    <DataTable
                        :options="DtOptions"
                        :data="companies"
                        :columns="columns"
                        class="nowrap table datatable-wrap table-responsive"
                    >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Checkpoint</th>
                                <th style="width: 15%">Status</th>
                                <th
                                    class="nk-tb-col nk-tb-col-tools text-end"
                                    style="width: 5%"
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
                                <div class="dropdown-menu !z-50">
                                    <ul class="link-list-opt no-bdr">
                                        <li
                                            @click="
                                                openModal(
                                                    '#createCompanyModal',
                                                    true,
                                                    route(
                                                        'companies.update',
                                                        rowData.id
                                                    ),
                                                    rowData,
                                                    'Edit Company'
                                                )
                                            "
                                            class="hover:text-blue-400"
                                        >
                                            <a href="#">
                                                <em
                                                    class="icon ni ni-edit"
                                                ></em>
                                                <span>Edit Name</span>
                                            </a>
                                        </li>
                                        <li
                                            @click="
                                                openModal(
                                                    '#statusCompanyModal',
                                                    true,
                                                    route(
                                                        'companies.update',
                                                        rowData.id
                                                    ),
                                                    rowData,
                                                    'Change Company Status'
                                                )
                                            "
                                        >
                                            <a
                                                class="hover:!text-red-400"
                                                href="#"
                                            >
                                                <em
                                                    class="icon ni ni-reload"
                                                ></em>
                                                <span>Change Status</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
        <!-- /datatable -->

        <!-- Modal -->
        <InputModal
            :modalId="'createCompanyModal'"
            :title="modalData.title"
            :url="modalData.url"
            :defaultValues="modalData.defaultValues"
            :isEdit="modalData.isEdit"
        />

        <StatusModal
            :modalId="'statusCompanyModal'"
            :title="modalData.title"
            :url="modalData.url"
            :defaultValues="modalData.defaultValues"
        />
        <!-- /Modal -->
    </AppLayout>
</template>

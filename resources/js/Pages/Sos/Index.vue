<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import moment from "moment";
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
    incidents: {
        type: Array,
        required: true,
    },
    incidentCount: {
        type: Number,
        required: true,
    },
});

const columns = [
    {
        data: "title",
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
        data: "created_at",
        className: "align-content-center",
        render: (data) => {
            return moment(data).fromNow();
        },
    },
    {
        data: "attachment",
        className: "align-content-center",
        render: (data) => {
            return `<a href="http://v2.patrol.vue-images.test/sos/attachment/${data}" target="_blank"><em class="ni ni-clip me-1"></em>Download</a>`;
        },
    },
    {
        hidden: true,
        data: null,
        className: "align-content-center",
    },
    {
        hidden: true,
        data: null,
        className: "align-content-center !z-[50]",
    },
];
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm mt-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">{{ title }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>You have total {{ incidentCount }} data.</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a
                            href="#"
                            class="btn btn-icon btn-trigger toggle-expand me-n1"
                            data-target="pageMenu"
                            ><em class="icon ni ni-more-v"></em
                        ></a>
                        <div
                            class="toggle-expand-content"
                            data-content="pageMenu"
                        >
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <div class="dropdown">
                                        <a
                                            href="#"
                                            class="dropdown-toggle btn btn-white btn-dim btn-outline-light"
                                            data-bs-toggle="dropdown"
                                            ><em
                                                class="icon ni ni-calender-date"
                                            >
                                            </em>
                                            <span>
                                                {{
                                                    moment().format(
                                                        "dddd, MMMM DD, YYYY"
                                                    )
                                                }}
                                            </span>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered card-preview">
                <div class="card-inner">
                    <DataTable
                        :options="DtOptions"
                        :data="incidents"
                        :columns="columns"
                        class="nowrap table datatable-wrap table-responsive"
                    >
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Company</th>
                                <th>Created At</th>
                                <th style="width: 15%">Attachment</th>
                                <th></th>
                                <th
                                    class="nk-tb-col nk-tb-col-tools text-end"
                                    style="width: 5%"
                                ></th>
                            </tr>
                        </thead>
                        <template #column-5="{ rowData }">
                            <span class="mx-2"
                                ><em class="ni ni-monitor me-1"></em
                                >{{ rowData.views }}</span
                            >
                            <span
                                ><em class="ni ni-chat me-1"></em
                                >{{ rowData.comments_count }}</span
                            >
                        </template>
                        <template #column-6="{ rowData }">
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
                                        <li>
                                            <a
                                                :href="
                                                    route(
                                                        'sos.show',
                                                        rowData.id
                                                    )
                                                "
                                                target="_blank"
                                            >
                                                <em
                                                    class="icon ni ni-external"
                                                ></em>
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
        </div>
    </AppLayout>
</template>

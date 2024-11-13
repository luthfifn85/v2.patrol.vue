<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, ref } from "vue";
import ModalButton from "@/Components/ModalButton.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import InputModal from "@/Pages/PatrolUser/InputModal.vue";
import StatusModal from "@/Pages/PatrolUser/StatusModal.vue";
import DtOptions from "@/datatables-config";

DataTable.use(DataTablesCore);

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    guards: {
        type: Array,
        required: true,
    },
    guardCount: {
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
    roles: {
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
        data: "email",
        className: "align-content-center",
    },
    {
        data: "company.name",
        className: "align-content-center",
    },
    {
        data: "patrol_location.name",
        className: "align-content-center",
        render: (data, type, row) => {
            if (
                row.patrol_role &&
                (row.patrol_role.id == 1 || row.patrol_role.id == 4)
            ) {
                return "All";
            }
            return data;
        },
    },
    {
        data: "patrol_role.name",
        className: "align-content-center",
        render: (data) => {
            return `<span class="badge rounded-pill bg-dark">${data}</span>`;
        },
    },
    {
        data: "is_active",
        className: "align-content-center justify-items-start",
        render: (data) => {
            return data
                ? `<span class="badge rounded-pill bg-success">Active</span>`
                : `<span class="badge rounded-pill bg-danger">Inactive</span>`;
        },
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
    title = props.title
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
        <div class="nk-content-wrap">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title mb-0">
                            {{ title }}
                        </h3>
                        <div class="nk-block-des text-soft">
                            <p>
                                You have total
                                <span class="text-base">{{ guardCount }}</span>
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
                                                        false,
                                                        route('guards.store'),
                                                        undefined,
                                                        'New Guard'
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
                        :data="guards"
                        :columns="columns"
                        class="nowrap table datatable-wrap table-responsive"
                    >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Role</th>
                                <th style="width: 15%">Status</th>
                                <th
                                    class="nk-tb-col nk-tb-col-tools text-end"
                                    style="width: 10%"
                                ></th>
                            </tr>
                        </thead>
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
                                        <li
                                            @click="
                                                openModal(
                                                    '#createModal',
                                                    true,
                                                    route(
                                                        'guards.change_password',
                                                        rowData.id
                                                    ),
                                                    rowData,
                                                    'Change Password'
                                                )
                                            "
                                            class="hover:text-blue-400"
                                        >
                                            <a href="#">
                                                <em
                                                    class="icon ni ni-unlock"
                                                ></em>
                                                <span>Change Password</span>
                                            </a>
                                        </li>
                                        <li
                                            @click="
                                                openModal(
                                                    '#statusModal',
                                                    true,
                                                    route(
                                                        'guards.reset_session',
                                                        rowData.id
                                                    ),
                                                    rowData,
                                                    'Reset Device Login'
                                                )
                                            "
                                        >
                                            <a
                                                class="hover:!text-red-400"
                                                href="#"
                                            >
                                                <em
                                                    class="icon ni ni-mobile"
                                                ></em>
                                                <span>Reset Device Login</span>
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

        <InputModal
            :roles="roles"
            :locations="locations"
            :companies="companies"
            :modalId="'createModal'"
            :modalData="modalData"
            :defaultValues="modalData.defaultValues"
        />

        <StatusModal
            :title="modalData.title"
            :modalId="'statusModal'"
            :url="modalData.url"
            :defaultValues="modalData.defaultValues"
        />
    </AppLayout>
</template>

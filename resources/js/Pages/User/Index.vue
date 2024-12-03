<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, ref } from "vue";
import ModalButton from "@/Components/ModalButton.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import InputModal from "@/Pages/User/InputModal.vue";
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
    users: {
        type: Array,
        required: true,
    },
    userCount: {
        type: Number,
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
        render: (data) => {
            return `<a href="mailto:${data}">${data}</a>`;
        },
    },
    {
        data: "role.name",
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
    {
        data: null,
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
        <div class="nk-block-head nk-block-head-sm mt-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">
                        {{ title }}
                    </h3>
                    <div class="nk-block-des text-soft">
                        <p>
                            You have total
                            <span class="text-base">{{ userCount }}</span>
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
                                                    '#createUserModal',
                                                    false,
                                                    route('users.store'),
                                                    undefined,
                                                    'New User'
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
                    :data="users"
                    :columns="columns"
                    class="nowrap table datatable-wrap table-responsive"
                >
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
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
                                                '#createUserModal',
                                                true,
                                                route(
                                                    'users.change_password',
                                                    rowData.id
                                                ),
                                                rowData,
                                                'Change Password'
                                            )
                                        "
                                        class="hover:text-blue-400"
                                    >
                                        <a href="#">
                                            <em class="icon ni ni-unlock"></em>
                                            <span>Change Password</span>
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
            :roles="roles"
            :modalId="'createUserModal'"
            :modalData="modalData"
        />
    </AppLayout>
</template>

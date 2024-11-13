<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, ref } from "vue";
import ModalButton from "@/Components/ModalButton.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import moment from "moment";
import DtOptions from "@/datatables-config";

DataTable.use(DataTablesCore);

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    patrols: {
        type: Array,
        required: true,
    },
    patrolCount: {
        type: Number,
        required: true,
    },
});

const columns = [
    {
        data: "patrol_checkpoint.name",
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
        data: "patrol_event.name",
        className: "align-content-center text-center",
        render: (data) => {
            const bgClass = data == "Safe" ? "bg-success" : "bg-danger";
            return `<span class="badge rounded-pill ${bgClass}">${data}</span>`;
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

<style lang="css">
tbody tr td:last-child div div {
    text-align: end;
}
</style>

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
                                <span class="text-base">{{ patrolCount }}</span>
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
                        :data="patrols"
                        :columns="columns"
                        class="nowrap table datatable-wrap table-responsive"
                    >
                        <thead>
                            <tr>
                                <th>Checkpoint Name</th>
                                <th>Location</th>
                                <th>Company</th>
                                <th>Created At</th>
                                <th class="text-center" style="width: 15%">Status</th>
                                <th
                                    class="nk-tb-col nk-tb-col-tools text-end"
                                    style="width: 10%"
                                ></th>
                            </tr>
                        </thead>
                        <template #column-5="{ rowData }">
                            <a
                                :href="route('patrols.show', rowData.id)"
                                class="btn btn-icon"
                                target="_blank"
                                ><em class="ni ni-monitor"></em
                            ></a>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

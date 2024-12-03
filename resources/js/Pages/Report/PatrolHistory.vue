<script setup>
import createDtOptions from "@/datatables-config";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import moment from "moment";
import { nextTick, onMounted, ref, watch } from "vue";
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
    patrols: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    company_id: "",
    patrol_location_id: "",
    month: "",
    year: "",
});

DataTable.use(DataTablesCore);
DataTablesCore.Buttons.jszip(jszip);
DataTablesCore.Buttons.pdfMake(pdfmake);

const DtOptions = createDtOptions();

const filteredLocations = ref([]);

const columns = [
    {
        data: "created_at",
        className: "align-content-center",
        render: (data) => {
            return moment(data).format("MMM DD, YYYY");
        },
    },
    {
        data: "created_at",
        className: "align-content-center",
        render: (data) => {
            return moment(data).format("hh:mm A");
        },
    },
    {
        data: "patrol_checkpoint.name",
        className: "align-content-center",
    },
    {
        data: "note",
        className: "align-content-center",
    },
    {
        data: "patrol_event.name",
        className: "align-content-center text-center",
        render: (data) => {
            const bgClass = data == "Safe" ? "bg-success" : "bg-danger";
            return `<span class="badge rounded-pill ${bgClass}">${data}</span>`;
        },
    },
    {
        data: null,
        className: "align-content-center md:text-end",
        render: (data) => {
            return `<a href="${route("patrols.show", data.id)}" target="_blank" class="btn btn-icon"><em class="ni ni-monitor"></em></a>`;
        },
    },
];

watch(
    () => form.company_id,
    (newCompanyId) => {
        const selectedCompany = props.companies.find(
            (company) => company.id == newCompanyId
        );
        filteredLocations.value = selectedCompany
            ? selectedCompany.patrol_locations
            : [];
        form.patrol_location_id = "";
    }
);

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const companyId = urlParams.get("company_id");
    const patrol_location_id = urlParams.get("patrol_location_id");
    const month = urlParams.get("month");
    const year = urlParams.get("year");

    if (companyId) form.company_id = companyId;
    if (patrol_location_id) {
        form.patrol_location_id = patrol_location_id;
        filteredLocations.value = props.companies.find(
            (company) => company.id == companyId
        ).patrol_locations;
        nextTick(() => {
            form.patrol_location_id = patrol_location_id;
        });
    }
    if (month) form.month = month;
    if (year) form.year = year;
});

const handleSubmitForm = () => {
    const queryParams = form.data();
    const url = route("reports.history", queryParams);

    form.get(url, {
        onSuccess: () => {
            form.reset();
            $(`#${props.modalId}`).modal("hide");
        },
        onError: () => {
            console.log("Error");
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm mt-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">{{ title }}</h3>
                    <div class="nk-block-des text-soft">
                        <code>* Field required</code>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <Link
                            :href="route('reports.index')"
                            class="btn btn-icon btn-trigger toggle-expand me-n1"
                            data-target="pageMenu"
                            ><em class="icon ni ni-arrow-left"></em
                        ></Link>
                        <div
                            class="toggle-expand-content"
                            data-content="pageMenu"
                        >
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <div class="dropdown">
                                        <Link
                                            :href="route('reports.index')"
                                            class="btn btn-white btn-dim btn-outline-light"
                                            ><em
                                                class="icon ni ni-arrow-left"
                                            ></em
                                        ></Link>
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
                    <form @submit.prevent="handleSubmitForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="get_company"
                                        >Company<code class="ms-1"
                                            >*</code
                                        ></label
                                    >
                                    <div class="form-control-wrap">
                                        <select
                                            class="form-select"
                                            name="company_id"
                                            id="get_company"
                                            v-model="form.company_id"
                                            required
                                        >
                                            <option
                                                value=""
                                                disabled
                                                selected
                                                hidden
                                            >
                                                Choose
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="get_location"
                                        >Location<code class="ms-1"
                                            >*</code
                                        ></label
                                    >
                                    <div class="form-control-wrap">
                                        <select
                                            class="form-select"
                                            name="patrol_location_id"
                                            id="get_location"
                                            :disabled="
                                                filteredLocations.length == 0
                                            "
                                            v-model="form.patrol_location_id"
                                            required
                                        >
                                            <option
                                                value=""
                                                disabled
                                                selected
                                                hidden
                                            >
                                                Choose
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="get_month"
                                        >Month<code class="ms-1">*</code></label
                                    >
                                    <div class="form-control-wrap">
                                        <select
                                            class="form-select"
                                            name="month"
                                            id="get_month"
                                            required
                                            v-model="form.month"
                                        >
                                            <option
                                                value=""
                                                disabled
                                                selected
                                                hidden
                                            >
                                                Choose
                                            </option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">
                                                September
                                            </option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="get_year"
                                        >Year<code class="ms-1">*</code></label
                                    >
                                    <div class="form-control-wrap">
                                        <select
                                            class="form-select"
                                            id="get_year"
                                            name="year"
                                            v-model="form.year"
                                            required
                                        >
                                            <option
                                                value=""
                                                disabled
                                                selected
                                                hidden
                                            >
                                                Choose
                                            </option>
                                            <option
                                                :value="moment().format('YYYY')"
                                            >
                                                {{ moment().format("YYYY") }}
                                            </option>
                                            <option
                                                :value="
                                                    moment()
                                                        .subtract(1, 'years')
                                                        .format('YYYY')
                                                "
                                            >
                                                {{
                                                    moment()
                                                        .subtract(1, "years")
                                                        .format("YYYY")
                                                }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button
                                        class="btn btn-dark"
                                        :disabled="form.processing"
                                    >
                                        <em class="ni ni-search me-1"></em>
                                        {{
                                            form.processing
                                                ? "Searching..."
                                                : "Search"
                                        }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="nk-block">
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
                                <th>Date</th>
                                <th>Time</th>
                                <th>Checkpoint</th>
                                <th>Note</th>
                                <th class="text-center" style="width: 15%">
                                    Status
                                </th>
                                <th
                                    class="nk-tb-col nk-tb-col-tools text-end"
                                    style="width: 10%"
                                ></th>
                            </tr>
                        </thead>
                        <template #column-5="{ rowData }">
                            <a
                                :href="route('patrols.show', rowData.id)"
                                target="_blank"
                                class="btn btn-icon"
                            >
                                <em class="ni ni-monitor"></em>
                            </a>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

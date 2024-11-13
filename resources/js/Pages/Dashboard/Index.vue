<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    companyCount: {
        type: Number,
        required: true,
    },
    locationCount: {
        type: Number,
        required: true,
    },
    checkpointCount: {
        type: Number,
        required: true,
    },
    userMobileCount: {
        type: Number,
        required: true,
    },
    userWebCount: {
        type: Number,
        required: true,
    },
    patrolTransactionCount: {
        type: Number,
        required: true,
    },
    latestLocations: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">
                        {{ title }}
                    </h3>
                    <div class="nk-block-des text-soft">
                        <p>Welcome to PATROL Dashboard.</p>
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
                                    <div class="drodown">
                                        <a
                                            href="#"
                                            class="dropdown-toggle btn btn-white btn-dim btn-outline-light"
                                            data-bs-toggle="dropdown"
                                            ><em
                                                class="icon ni ni-calender-date"
                                            ></em>
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

        <!-- panel -->
        <div class="nk-block">
            <div class="row gy-gs">
                <!-- project overview -->
                <div class="col-md-4">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group align-start mb-0">
                                <div class="card-title card-title-sm">
                                    <h6 class="title">Number of Company</h6>
                                </div>
                                <div class="card-tools">
                                    <em
                                        class="card-hint icon ni ni-building"
                                    ></em>
                                </div>
                            </div>
                            <div class="card-amount">
                                <span class="amount">
                                    {{ companyCount }}
                                </span>
                            </div>
                            <div class="invest-data">
                                <div class="invest-data-amount g-2">
                                    <div class="invest-data-history">
                                        <div class="title">Location</div>
                                        <div class="amount">
                                            {{ locationCount }}
                                        </div>
                                    </div>
                                    <div class="invest-data-history">
                                        <div class="title">Checkpoint</div>
                                        <div class="amount">
                                            {{ checkpointCount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="invest-data-ck"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /project overview -->

                <!-- user overview -->
                <div class="col-md-4">
                    <div class="card card-bordered card-full">
                        <div class="card-inner">
                            <div class="card-title-group align-start mb-0">
                                <div class="card-title card-title-sm">
                                    <h6 class="title">Number of Users</h6>
                                </div>
                                <div class="card-tools">
                                    <em class="card-hint icon ni ni-users"></em>
                                </div>
                            </div>
                            <div class="card-amount">
                                <span class="amount">
                                    {{ userMobileCount + userWebCount }}
                                </span>
                            </div>
                            <div class="invest-data">
                                <div class="invest-data-amount g-2">
                                    <div class="invest-data-history">
                                        <div class="title">CMS</div>
                                        <div class="amount">
                                            {{ userWebCount }}
                                        </div>
                                    </div>
                                    <div class="invest-data-history">
                                        <div class="title">Mobile</div>
                                        <div class="amount">
                                            {{ userMobileCount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="invest-data-ck"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /user overview -->

                <!-- patrol overview -->
                <div class="col-md-4">
                    <div class="card card-bordered h-100">
                        <div class="card-inner pb-0">
                            <div class="card-title-group pt-1">
                                <div class="card-title">
                                    <h6 class="title">Patrol</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-inner pt-0">
                            <div class="invest-ov gy-1">
                                <div class="subtitle">Transactions</div>
                                <div class="invest-ov-details">
                                    <div class="invest-ov-stats">
                                        <div>
                                            <span
                                                class="amount d-flex align-items-end"
                                            >
                                                {{ patrolTransactionCount }}
                                            </span>
                                        </div>
                                        <div class="title">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /patrol overview -->

                <!-- recent new project -->
                <div class="col-xl-12 col-xxl-12">
                    <div class="card card-bordered card-full">
                        <div class="card-inner border-bottom">
                            <div class="card-title-group">
                                <div class="card-title card-title-sm">
                                    <h6 class="title">Recent New Location</h6>
                                </div>
                                <div class="card-tools"></div>
                            </div>
                        </div>
                        <div class="nk-tb-list">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col tb-col">
                                    <span>Company Name</span>
                                </div>
                                <div class="nk-tb-col tb-col">
                                    <span>Location</span>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    <span>Starting Date</span>
                                </div>
                            </div>

                            <div
                                class="nk-tb-item"
                                v-for="location in latestLocations"
                                :key="location.id"
                            >
                                <div class="nk-tb-col tb-col">
                                    <div class="align-center">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead">{{
                                                    location.company.name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col">
                                    <div class="user-card">
                                        <div class="user-info">
                                            <span class="tb-lead">{{
                                                location.name
                                            }}</span>
                                            <span>{{ location.address }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    <span
                                        ><em class="icon ni ni-clock"></em
                                        ><span>{{
                                            moment(location.created_at).format(
                                                "MMM DD, YYYY"
                                            )
                                        }}</span></span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /recent new project -->
            </div>
        </div>
        <!-- /panel -->
    </AppLayout>
</template>

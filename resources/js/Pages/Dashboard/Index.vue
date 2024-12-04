<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import { Swiper, SwiperSlide } from "swiper/vue";
import { EffectFade, Navigation, Pagination, Autoplay } from "swiper/modules";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Filler,
} from "chart.js";
import { Line } from "vue-chartjs";
import { Link } from "@inertiajs/vue3";

// Import Swiper styles
import "swiper/css";

import "swiper/css/effect-fade";
import "swiper/css/navigation";
import "swiper/css/pagination";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    latestPatrol: {
        type: Object,
        required: true,
    },
    patrolCurrentYearCount: {
        type: Number,
        required: true,
    },
    checkpointCount: {
        type: Number,
        required: true,
    },
    monthlyData: {
        type: Object,
        required: true,
    },
    incidentCount: {
        type: Number,
        required: true,
    },
});

ChartJS.register(
    CategoryScale,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip,
    Legend,
    Filler
);
const labels = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];

const datasets = [
    {
        // Label and data configuration
        label: "Patrol Overview",
        data: labels.map((_, index) => props.monthlyData[index + 1] || 0),
        backgroundColor: "rgba(220, 53, 69, 0.2)", // Color for the space under the line
        borderColor: "black", // Line color
        borderWidth: 2,
        pointBackgroundColor: "rgba(220, 53, 69, 1)", // Point color
        pointBorderColor: "#fff",
        pointHoverRadius: 5,
        tension: 0.4, // Smooth the line
        fill: true, // Enable the fill under the line
    },
];

const options = {
    responsive: true,
    maintainAspectRatio: false, // Ensures chart adapts well to its container
    plugins: {
        legend: {
            display: false,
            labels: {
                color: "#6783b8",
                boxWidth: 30,
                padding: 0,
            },
        },
        tooltip: {
            enabled: true,
            backgroundColor: "#eff6ff",
            titleFont: {
                size: 13,
            },
            titleColor: "#6783b8",
            titleMarginBottom: 6,
            bodyColor: "#9eaecf",
            bodyFont: {
                size: 12,
            },
            bodySpacing: 4,
            padding: 10,
            displayColors: false,
        },
    },
    scales: {
        x: {
            title: {
                display: true,
                text: "Month",
                color: "#9eaecf",
                font: {
                    size: 12,
                },
            },
            ticks: {
                color: "#9eaecf",
                font: {
                    size: 9,
                },
            },
            grid: {
                display: false, // Disable the grid lines for the x-axis
            },
        },
        y: {
            title: {
                display: true,
                text: "Total",
                color: "#9eaecf",
                font: {
                    size: 12,
                },
            },
            beginAtZero: true,
            ticks: {
                color: "#9eaecf",
                font: {
                    size: 10,
                },
                stepSize: 1, // Set the step size for the y-axis
            },
            grid: {
                display: false, // Disable the grid lines for the x-axis
            },
        },
    },
};

const modules = [EffectFade, Navigation, Pagination, Autoplay];
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm mt-4 sm:mt-0">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">{{ title }}</h3>
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
                                    <div class="dropdown">
                                        <a
                                            href="#"
                                            class="dropdown-toggle btn btn-white btn-dim btn-outline-light"
                                            data-bs-toggle="dropdown"
                                        >
                                            <em
                                                class="icon ni ni-calender-date"
                                            ></em
                                            ><span
                                                >{{
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
            <div class="row g-gs">
                <div class="col-md-4">
                    <div class="card card-bordered h-100 relative">
                        <Swiper
                            :spaceBetween="30"
                            :effect="'fade'"
                            :navigation="true"
                            :pagination="{
                                clickable: true,
                            }"
                            :autoplay="{
                                delay: 2500,
                                disableOnInteraction: false,
                            }"
                            :modules="modules"
                            class="mySwiper max-w-full h-full"
                            :style="{
                                '--swiper-pagination-color': '#c61f25',
                                '--swiper-navigation-color': '#fff',
                            }"
                        >
                            <SwiperSlide
                                v-for="media in latestPatrol.media_items"
                                :key="media.id"
                                class="h-full"
                            >
                                <img
                                    :src="`http://v2.patrol.vue-images.test/patrol/${media.filename}`"
                                    class="h-full object-cover"
                                    width="100%"
                                />
                            </SwiperSlide>
                        </Swiper>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-bordered h-100">
                        <div class="sp-plan-info card-inner">
                            <div class="row gx-0 gy-3">
                                <div class="col-xl-9 col-sm-8">
                                    <div class="sp-plan-name">
                                        <h6 class="title">
                                            <a href="#" class="text-dark"
                                                >Latest Patrol
                                                <em class="ni ni-security"></em
                                            ></a>
                                        </h6>
                                        <p>
                                            {{
                                                moment(
                                                    latestPatrol.created_at
                                                ).format("MMM DD, YYYY")
                                            }}
                                            at
                                            {{
                                                moment(
                                                    latestPatrol.created_at
                                                ).format("h:mm A")
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-4">
                                    <div class="sp-plan-opt ms-5">
                                        <Link
                                            :href="
                                                route(
                                                    'patrols.show',
                                                    latestPatrol.id
                                                )
                                            "
                                        >
                                            See Details
                                            <em
                                                class="ni ni-chevron-right ms-1"
                                            ></em>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sp-plan-desc card-inner">
                            <ul class="row gx-0">
                                <li class="col-md-6">
                                    <p>
                                        <span class="text-soft"
                                            >Checkpoint</span
                                        >
                                        {{
                                            latestPatrol.patrol_checkpoint.name
                                        }}
                                    </p>
                                </li>
                                <li class="col-md-6">
                                    <p>
                                        <span class="text-soft">Event</span>
                                        {{ latestPatrol.patrol_event.name }}
                                    </p>
                                </li>
                                <li class="col-md-6">
                                    <p>
                                        <span class="text-soft"
                                            >Location / Company</span
                                        >
                                        {{ latestPatrol.patrol_location.name }}
                                        ({{ latestPatrol.company.name }})
                                    </p>
                                </li>
                                <li class="col-md-6">
                                    <p>
                                        <span class="text-soft"
                                            >Views / Comment</span
                                        >
                                        {{
                                            Number(
                                                latestPatrol.views
                                            ).toLocaleString()
                                        }}
                                        /
                                        {{
                                            Number(
                                                latestPatrol.comments_count
                                            ).toLocaleString()
                                        }}
                                        Users
                                    </p>
                                </li>
                                <li class="col-md-12">
                                    <p>
                                        <span class="text-soft"
                                            >Created By</span
                                        >
                                        {{ latestPatrol.patrol_user.name }}
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nk-block">
            <div class="row g-gs">
                <div class="col-md-8">
                    <div class="card card-bordered h-100">
                        <div class="card-inner">
                            <div class="card-title-group align-start gx-3 mb-3">
                                <div class="card-title">
                                    <h6 class="title">Patrol Overview</h6>
                                    <p>Total Transactions.</p>
                                </div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <a
                                            href="#"
                                            class="btn btn-dark text-white d-none d-sm-inline-flex"
                                            ><em
                                                class="icon ni ni-calendar"
                                            ></em
                                            ><span
                                                >Year
                                                {{
                                                    moment().format("YYYY")
                                                }}</span
                                            ></a
                                        >
                                        <a
                                            href="#"
                                            class="btn btn-icon btn-dark text-white d-sm-none"
                                            ><em
                                                class="icon ni ni-calendar"
                                            ></em
                                        ></a>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="nk-sale-data-group align-center justify-between gy-3 gx-5"
                            >
                                <div class="nk-sale-data">
                                    <span class="amount">
                                        {{
                                            Number(
                                                patrolCurrentYearCount.toLocaleString()
                                            )
                                        }}
                                    </span>
                                </div>
                                <div class="nk-sale-data">
                                    <span class="amount sm">
                                        {{ checkpointCount }}
                                        <small>Checkpoints</small></span
                                    >
                                </div>
                            </div>
                            <div class="nk-sales-ck large pt-4">
                                <Line
                                    :data="{ labels, datasets }"
                                    :options="options"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-bordered h-100 pricing text-center">
                        <div class="card-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pricing-body">
                                        <div
                                            class="pricing-media justify-self-center"
                                        >
                                            <img
                                                src="/images/logo-abs.png"
                                                width="50"
                                            />
                                        </div>
                                        <div
                                            class="pricing-title w-220px mx-auto"
                                        >
                                            <h5 class="title">SOS</h5>
                                            <span class="sub-text">Total</span>
                                        </div>
                                        <div class="pricing-amount">
                                            <div class="amount">
                                                {{
                                                    Number(
                                                        incidentCount.toLocaleString()
                                                    )
                                                }}
                                            </div>
                                            <span class="bill">Incidents</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /panel -->
    </AppLayout>
</template>

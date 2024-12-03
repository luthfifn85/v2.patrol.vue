<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>
<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm mt-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">{{ title }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>Welcome to PATROL {{ title }}.</p>
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
                                            ></em
                                            ><span>
                                                {{
                                                    moment().format(
                                                        "ddd. MMMM D, YYYY"
                                                    )
                                                }}
                                            </span></a
                                        >
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="support-topic-item card card-bordered">
                <Link
                    class="support-topic-block card-inner"
                    :href="route('reports.history')"
                >
                    <div class="support-topic-context">
                        <h5 class="support-topic-title title">History</h5>
                        <div class="support-topic-info">
                            You can find all of the patrol history.
                        </div>
                    </div>
                    <div class="support-topic-action">
                        <em class="icon ni ni-chevron-right"></em>
                    </div>
                </Link>
            </div>
        </div>
        <div class="nk-block">
            <div class="support-topic-item card card-bordered">
                <Link
                    class="support-topic-block card-inner"
                    :href="route('reports.incident')"
                >
                    <div class="support-topic-context">
                        <h5 class="support-topic-title title">Incident</h5>
                        <div class="support-topic-info">
                            You can find all of the SOS / Incident history.
                        </div>
                    </div>
                    <div class="support-topic-action">
                        <em class="icon ni ni-chevron-right"></em>
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

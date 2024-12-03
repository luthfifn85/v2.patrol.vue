<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import PhotoSwipeLightbox from "photoswipe/lightbox";
import { onUnmounted, onMounted } from "vue";

import "photoswipe/style.css";

// Props declaration
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    patrol: {
        type: Object,
        required: true,
    },
});

const galleryID = "my-gallery";
let lightbox;

onMounted(() => {
    if (!lightbox) {
        lightbox = new PhotoSwipeLightbox({
            gallery: `#${galleryID}`,
            children: "a",
            pswpModule: () => import("photoswipe"),
        });
        lightbox.init();
    }
});

onUnmounted(() => {
    if (lightbox) {
        lightbox.destroy();
        lightbox = null;
    }
});
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm mt-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title mb-0">
                        {{ title }} Information
                    </h3>
                    <div class="nk-block-des text-soft">
                        <p>
                            {{ patrol.patrol_checkpoint.name }} ({{
                                patrol.patrol_location.name
                            }}, {{ patrol.company.name }})
                        </p>
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
                                            onclick="window.print()"
                                            class="dropdown-toggle btn btn-white btn-dim btn-outline-light"
                                            data-bs-toggle="dropdown"
                                            ><em class="icon ni ni-printer"></em
                                        ></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div
                        class="row flex-wrap px-2 md:!mx-[3px] md:flex-nowrap md:px-0 md:gap-x-3"
                        v-if="patrol?.media_items?.length"
                    >
                        <!-- MediaItems -->
                        <div
                            class="col-12 mb-2 rounded-md p-0 overflow-clip w-full md:!w-fit"
                            v-for="media in patrol.media_items"
                            :key="media.id"
                        >
                            <div :id="galleryID">
                                <a
                                    :href="`http://v2.patrol.vue-images.test/patrol/${media.filename}`"
                                    :data-pswp-width="400"
                                    :data-pswp-height="400"
                                    class="max-w-100 w-100"
                                    target="_blank"
                                    rel="noreferrer"
                                >
                                    <img
                                        class="w-full md:max-w-[210px] md:w-[210px]"
                                        :src="`http://v2.patrol.vue-images.test/patrol/${media.filename}`"
                                        alt=""
                                    />
                                </a>
                            </div>
                        </div>
                        <!-- MediaItems -->
                    </div>
                    <div class="row g-gs flex-lg-row-reverse pt-3">
                        <div class="col-lg-12">
                            <div class="product-details entry me-xxl-3">
                                <p class="text-dark">
                                    <em class="ni ni-calendar me-1"></em>
                                    {{
                                        moment(patrol.created_at).format(
                                            "dddd, MMMM DD, YYYY"
                                        )
                                    }}
                                    at
                                    {{
                                        moment(patrol.created_at).format(
                                            "HH:mm A"
                                        )
                                    }}
                                </p>

                                <p>{{ patrol.note }}</p>
                                <ul
                                    class="list list-sm list-checked text-black"
                                >
                                    <li>
                                        Event:
                                        <strong class="underline">
                                            {{ patrol.patrol_event.name }}
                                        </strong>
                                    </li>
                                    <li>
                                        Created By:
                                        <strong>
                                            {{ patrol.patrol_user.name }}
                                        </strong>
                                    </li>
                                    <li>
                                        Views:
                                        <strong>
                                            {{ patrol.views }}
                                        </strong>
                                    </li>
                                    <li>
                                        Coordinates:
                                        <strong>
                                            {{ patrol.latitude }},
                                            {{ patrol.longitude }}
                                        </strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="card card-bordered w-100 mt-2">
                                <iframe
                                    :src="`https://www.google.com/maps?q=${patrol.latitude},${patrol.longitude}&hl=es;z=14&output=embed`"
                                    class="google-map border-0"
                                    allowfullscreen=""
                                    loading="lazy"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments -->
        <div class="nk-block">
            <div class="card card-bordered">
                <div class="card-inner">
                    <h6 class="title">
                        Comments ({{ patrol.comments?.length || 0 }})
                    </h6>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div
                class="card card-bordered"
                v-for="comment in patrol.comments"
                :key="comment.id"
            >
                <div class="card-inner">
                    <div class="ticket-msg-from">
                        <div class="ticket-msg-user user-card">
                            <div class="user-info">
                                <span class="lead-text">{{
                                    comment.patrol_user.name
                                }}</span>
                                <span class="text-soft">{{
                                    comment.patrol_user.patrol_role.name
                                }}</span>
                            </div>
                        </div>
                        <div class="ticket-msg-date">
                            <span class="sub-text">{{
                                moment(comment.created_at).fromNow()
                            }}</span>
                        </div>
                    </div>
                    <div class="text-justify-left">
                        <p>{{ comment.message }}</p>
                    </div>
                </div>
            </div>
            <div class="card card-bordered" v-if="!patrol.comments?.length">
                <div class="card-inner">
                    <div class="text-center">
                        <p><em class="icon ni ni-chat"></em></p>
                        No comments yet.
                    </div>
                </div>
            </div>
        </div>
        <!-- /Comments -->

        <!-- <div class="mt-2">
            <div class="nk-block-between-md g-3">
                <div class="g">
                    {{ $comment->links() }}
                </div>
                <div class="g">
                    <div
                        class="pagination-goto d-flex justify-content-center justify-content-md-start gx-1"
                    >
                        <div>
                            Page <strong>{{ $comment->currentPage() }}</strong>
                        </div>
                        <div>
                            OF <strong>{{ $comment->lastPage() }}</strong>
                        </div>
                        <div>
                            TOTAL <strong>{{ $comment->total() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </AppLayout>
</template>

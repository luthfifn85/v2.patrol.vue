<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import { Swiper, SwiperSlide } from "swiper/vue";
import { FreeMode, Navigation, Thumbs } from "swiper/modules";
import { ref } from "vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/thumbs";

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

// Swiper setup
const thumbsSwiper = ref(null);

// Function to set the thumbs swiper instance
const setThumbsSwiper = (swiper) => {
    thumbsSwiper.value = swiper;
};

// Modules to be used with Swiper
const modules = [FreeMode, Navigation, Thumbs];
</script>

<template>
    <AppLayout>
        <div class="nk-content-wrap">
            <div class="nk-block-head nk-block-head-sm">
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
                                                ><em
                                                    class="icon ni ni-printer"
                                                ></em
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
                        <div class="row pb-3">
                            <div class="col-lg-12">
                                <Swiper
                                    :spaceBetween="10"
                                    :navigation="true"
                                    :thumbs="{ swiper: thumbsSwiper }"
                                    :modules="modules"
                                    class="mySwiper2 h-fit !max-h-[500px]"
                                >
                                    <SwiperSlide
                                        v-for="image in patrol.media_items"
                                        :key="image.id"
                                        :lazy="true"
                                        :virtual-index="image.id"
                                        class="max-h-[500px]"
                                        ><img
                                            :src="`/images/${image.filename}`"
                                            class="object-contain h-[500px] w-full"
                                    /></SwiperSlide>
                                </Swiper>
                                <Swiper
                                    @swiper="setThumbsSwiper"
                                    :spaceBetween="5"
                                    :slidesPerView="4"
                                    :freeMode="true"
                                    :watchSlidesProgress="true"
                                    :modules="modules"
                                    class="mySwiper mt-1"
                                >
                                    <SwiperSlide
                                        v-for="image in patrol.media_items"
                                        :key="image.id"
                                        :lazy="true"
                                        :virtual-index="image.id"
                                        class="w-[200px] h-[200px]"
                                        ><img
                                            class="w-full h-[200px] object-cover"
                                            :src="`/images/${image.filename}`"
                                    /></SwiperSlide>
                                </Swiper>
                            </div>
                        </div>
                        <hr class="hr border-light" />
                        <div class="row g-gs flex-lg-row-reverse pt-3">
                            <div class="col-lg-12">
                                <div class="product-details entry me-xxl-3">
                                    <p>{{ patrol.note }}</p>
                                    <ul class="list list-sm list-checked">
                                        <li>
                                            Date:
                                            {{
                                                moment(
                                                    patrol.created_at
                                                ).format(
                                                    "MMM DD, YYYY. hh:mm A"
                                                )
                                            }}
                                        </li>
                                        <li>
                                            Event:
                                            {{ patrol.patrol_event.name }}
                                        </li>
                                        <li>
                                            Coordinates: {{ patrol.latitude }},
                                            {{ patrol.longitude }}
                                        </li>
                                        <li>
                                            Created By:
                                            {{ patrol.patrol_user.name }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

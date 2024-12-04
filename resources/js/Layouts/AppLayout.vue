<!-- resources/js/Layouts/AppLayout.vue -->
<script setup>
import Header from "@/Includes/Header.vue";
import Navigation from "@/Includes/Navigation.vue";
import Footer from "@/Includes/Footer.vue";
import Toast from "@/Components/Toast.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const page = usePage();
const flashMessages = ref({
    success: page.props.flash.success,
    error: page.props.flash.error,
});
// Watch for flash message changes
watch(
    () => page.props.flash,
    (newFlash) => {
        flashMessages.value = {
            success: newFlash.success,
            error: newFlash.error,
        };
    }
);

const showSideNav = ref(false);
const handleSideNav = () => {
    showSideNav.value = !showSideNav.value;
};
</script>

<style lang="css">
/*  */

.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 12px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #c6c6c6;
    border-radius: 10px;
    border: 2px solid #f1f1f1;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: grey;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 99;
}

div.datatable-wrap > div > div.dt-scroll-head > div > table {
    border-bottom: 1px solid #e5e7eb;
    border-right: none;
    border-left: none;
    border-top: none;
}

div.datatable-wrap.mb-1 > div > div.dt-scroll-head table thead tr th {
    border-bottom: none !important;
}

.dt-scroll-body {
    min-height: 300px;
}

.dt-scroll-body > table.nowrap.datatable-wrap.dataTable {
    border-left: none;
    border-right: none;
}

.dt-scroll-body table {
    border-top: none;
    border-bottom: 1px solid #e5e7eb;
}

table.table-responsive.dataTable.dtfc-has-start.dtfc-has-left thead {
    display: none;
}

.dt-scroll-foot {
    display: none;
}

.dropdown .dropdown-menu ul li a em {
    text-align: center;
}

.dropdown .dropdown-menu ul li a em ~ span {
    margin-left: 5px !important;
}
</style>

<template>
    <div class="nk-app-root">
        <div class="nk-main">
            <div class="nk-wrap">
                <div class="nk-header nk-header-fixed is-light">
                    <div class="container-lg wide-xl">
                        <Header :toggleSideNav="handleSideNav" />
                    </div>
                </div>

                <div class="nk-content pt-1">
                    <div class="container wide-xl">
                        <div class="nk-content-inner">
                            <div
                                class="nk-aside toggle-screen-lg mobile-menu overflow-y-scroll custom-scrollbar"
                                :class="{ 'content-active': showSideNav }"
                                data-content="sideNav"
                                data-toggle-overlay="true"
                                data-toggle-screen="lg"
                                data-toggle-body="true"
                            >
                                <Navigation />
                                <div class="nk-aside-close">
                                    <a
                                        href="#"
                                        class="toggle"
                                        data-target="sideNav"
                                    >
                                        <em class="icon ni ni-cross"></em>
                                    </a>
                                </div>
                            </div>

                            <div class="nk-content-body">
                                <div class="nk-content-wrap">
                                    <slot />
                                </div>
                                <Footer />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Toast
        :success-message="flashMessages.success"
        :error-message="flashMessages.error"
    />

    <div v-if="showSideNav" class="overlay" @click="handleSideNav"></div>
</template>

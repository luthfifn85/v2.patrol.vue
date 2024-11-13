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
</script>

<template>
    <div class="nk-app-root">
        <div class="nk-main">
            <div class="nk-wrap">
                <div class="nk-header nk-header-fixed is-light">
                    <div class="container-lg wide-xl">
                        <Header />
                    </div>
                </div>

                <div class="nk-content pt-1">
                    <div class="container wide-xl">
                        <div class="nk-content-inner">
                            <div
                                class="nk-aside"
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
</template>

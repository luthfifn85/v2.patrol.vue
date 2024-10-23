<script setup>
import Header from '@/Includes/Header.vue';
import Navigation from '@/Includes/Navigation.vue';
import Footer from '@/Includes/Footer.vue';
</script>

<template>
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap ">
                <div class="nk-header nk-header-fixed is-light">
                    <div class="container-lg wide-xl">

                        <!-- header -->
                        <Header />
                        <!-- /header -->

                    </div>
                </div>

                <!-- left navigation -->
                <div class="nk-content ">
                    <div class="container wide-xl">
                        <div class="nk-content-inner">
                            <div class="nk-aside" data-content="sideNav" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
                                <div class="nk-sidebar-menu" data-simplebar>

                                    <Navigation />

                                </div>
                                <div class="nk-aside-close">
                                    <a href="#" class="toggle" data-target="sideNav"><em class="icon ni ni-cross"></em></a>
                                </div>
                            </div>

                            <!-- content -->
                            <div class="nk-content-body">
                                <div class="nk-content-wrap">

                                    <!-- slot -->
                                    <slot />
                                    <!-- /slot -->

                                </div>

                                <!-- footer -->
                                <Footer />
                                <!-- /footer -->

                            </div>
                            <!-- /content -->

                        </div>
                    </div>
                </div>
                <!-- /left navigation -->

            </div>
        </div>
    </div>
</template>

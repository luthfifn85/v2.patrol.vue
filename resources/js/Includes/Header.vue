<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    toggleSideNav: Function,
});
</script>

<style lang="css">
.dropdown-menu-s1 {
    border-top: 3px solid #c61f25;
}
</style>

<template>
    <div class="nk-header-wrap">
        <!-- logo -->
        <div class="nk-header-brand">
            <!-- <a href="html/index.html" class="logo-link"> -->
            <Link :href="route('dashboard.index')" class="logo-link">
                <img
                    class="logo-light logo-img logo-img-dashboard"
                    src="/images/logo-dark-abs.png"
                    alt="logo"
                />
                <img
                    class="logo-dark logo-img logo-img-dashboard"
                    src="/images/logo-dark-abs.png"
                    alt="logo-dark"
                />
            </Link>
            <!-- </a> -->
        </div>
        <!-- /logo -->

        <!-- left header -->
        <div class="nk-header-menu">
            <ul class="nk-menu nk-menu-main">
                <li
                    class="nk-menu-item"
                    :class="{ active: route().current('dashboard.index') }"
                >
                    <Link
                        :href="route('dashboard.index')"
                        class="nk-menu-link active"
                    >
                        <span class="nk-menu-text">Home</span>
                    </Link>
                </li>
                <li
                    class="nk-menu-item"
                    :class="{
                        active:
                            route().current('reports.index') ||
                            route().current('reports.history') ||
                            route().current('reports.incident'),
                    }"
                >
                    <Link :href="route('reports.index')" class="nk-menu-link">
                        <span class="nk-menu-text">Report</span>
                    </Link>
                </li>
                <li
                    class="nk-menu-item"
                    :class="{ active: route().current('faqs.index') }"
                >
                    <Link :href="route('faqs.index')" class="nk-menu-link">
                        <span class="nk-menu-text">FAQs</span>
                    </Link>
                </li>
            </ul>
        </div>
        <!-- /left header -->

        <!-- right header -->
        <div class="nk-header-tools">
            <ul class="nk-quick-nav">
                <li class="dropdown user-dropdown">
                    <a
                        href="#"
                        class="dropdown-toggle me-lg-n1"
                        data-bs-toggle="dropdown"
                    >
                        <div class="user-toggle">
                            <div class="user-avatar sm">
                                <img src="/images/avatar/default.webp" />
                            </div>
                            <div class="user-info">
                                <span class="lead-text">{{
                                    $page.props.auth.user.name
                                }}</span>
                            </div>
                        </div>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1"
                    >
                        <div
                            class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block"
                        >
                            <div class="user-card">
                                <div class="user-avatar">
                                    <img src="/images/avatar/default.webp" />
                                </div>
                                <div class="user-info">
                                    <span class="lead-text">{{
                                        $page.props.auth.user.name
                                    }}</span>
                                    <span class="sub-text">{{
                                        $page.props.auth.user.email
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-inner">
                            <ul class="link-list">
                                <li>
                                    <Link :href="route('profiles.edit')"
                                        ><em class="icon ni ni-user-alt"></em
                                        ><span>My Profile</span></Link
                                    >
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown-inner">
                            <ul class="link-list">
                                <li>
                                    <Link
                                        as="a"
                                        class="flex items-center"
                                        :href="route('logout')"
                                        method="post"
                                    >
                                        <em
                                            class="icon ni ni-signout"
                                        ></em>
                                        <span> Logout </span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="d-lg-none">
                    <a
                        href="#"
                        class="toggle nk-quick-nav-icon me-n1"
                        data-target="sideNav"
                        @click="toggleSideNav"
                        ><em class="icon ni ni-menu"></em
                    ></a>
                </li>
            </ul>
        </div>
        <!-- /right header -->
    </div>
</template>

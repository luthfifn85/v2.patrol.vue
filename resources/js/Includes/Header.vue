<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="nk-header-wrap">

        <!-- logo -->
        <div class="nk-header-brand">
            <a href="html/index.html" class="logo-link">
                <img class="logo-light logo-img" src="images/logo.png" srcset="images/logo2x.png 2x" alt="logo">
                <img class="logo-dark logo-img" src="images/logo-dark.png" srcset="images/logo-dark2x.png 2x" alt="logo-dark">
            </a>
        </div>
        <!-- /logo -->

        <!-- left header -->
        <div class="nk-header-menu">
            <ul class="nk-menu nk-menu-main">
                <li class="nk-menu-item">
                    <Link :href="route('dashboard')" class="nk-menu-link">
                        <span class="nk-menu-text">Home</span>
                    </Link>
                </li>
                <li class="nk-menu-item">
                    <Link :href="route('report')" class="nk-menu-link">
                        <span class="nk-menu-text">Report</span>
                    </Link>
                </li>
            </ul>
        </div>
        <!-- /left header -->

        <!-- right header -->
        <div class="nk-header-tools">
            <ul class="nk-quick-nav">
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle me-lg-n1" data-bs-toggle="dropdown">
                        <div class="user-toggle">
                            <div class="user-avatar sm">
                                <img src="images/avatar/default.webp">
                            </div>
                            <div class="user-info">
                                <span class="lead-text">{{ $page.props.auth.user.name }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1">
                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                            <div class="user-card">
                                <div class="user-avatar">
                                    <img src="images/avatar/default.webp">
                                </div>
                                <div class="user-info">
                                    <span class="lead-text">{{ $page.props.auth.user.name }}</span>
                                    <span class="sub-text">{{ $page.props.auth.user.email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-inner">
                            <ul class="link-list">
                                <li><Link :href="route('profile')"><em class="icon ni ni-user-alt"></em><span>My Profile</span></Link></li>
                                <li><Link :href="route('faqs')"><em class="icon ni ni-activity-alt"></em><span>FAQs</span></Link></li>
                                <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                            </ul>
                        </div>
                        <div class="dropdown-inner">
                            <ul class="link-list">
                                <li><Link :href="route('logout')" method="post"><em class="icon ni ni-signout"></em><span>Logout</span></Link></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="d-lg-none">
                    <a href="#" class="toggle nk-quick-nav-icon me-n1" data-target="sideNav"><em class="icon ni ni-menu"></em></a>
                </li>
            </ul>
        </div>
        <!-- /right header -->

    </div>
</template>

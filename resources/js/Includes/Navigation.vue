<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>

    <!-- small header -->
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
    <!-- /small header -->

    <!-- menu -->
    <ul class="nk-menu">
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">Setup</h6>
        </li>
        <li class="nk-menu-item">
            <Link :href="route('company')" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-building"></em></span>
                <span class="nk-menu-text">Company</span>
            </Link>
        </li>
        <li class="nk-menu-item">
            <Link :href="route('location')" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-location"></em></span>
                <span class="nk-menu-text">Location</span>
            </Link>
        </li>
        <li class="nk-menu-item">
            <Link :href="route('checkpoint')" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-map-pin"></em></span>
                <span class="nk-menu-text">Checkpoint</span>
            </Link>
        </li>
        <li class="nk-menu-item">
            <Link :href="route('guard')" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-security"></em></span>
                <span class="nk-menu-text">Guard</span>
            </Link>
        </li>
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">Operation</h6>
        </li>
        <li class="nk-menu-item">
            <Link :href="route('patrol')" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-shield-check"></em></span>
                <span class="nk-menu-text">Patrol</span>
            </Link>
        </li>
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">Administrator</h6>
        </li>
        <li class="nk-menu-item">
            <Link :href="route('user')" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                <span class="nk-menu-text">User</span>
            </Link>
        </li>
    </ul>
    <!-- /menu -->

</template>

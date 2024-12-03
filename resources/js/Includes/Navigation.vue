<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <!-- small header -->
    <ul class="nk-menu nk-menu-main">
        <li class="nk-menu-item">
            <Link
                :href="route('dashboard.index')"
                class="nk-menu-link active"
                :class="{ active: route().current('dashboard.index') }"
            >
                <span class="nk-menu-text">Home</span>
            </Link>
        </li>
        <li class="nk-menu-item">
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
    <!-- /small header -->

    <!-- menu -->
    <ul class="nk-menu">
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">Operation</h6>
        </li>
        <li
            class="nk-menu-item"
            :class="{ active: route().current('dashboard.index') }"
        >
            <Link :href="route('dashboard.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-growth"></em
                ></span>
                <span class="nk-menu-text">Dashboard</span>
            </Link>
        </li>

        <li
            class="nk-menu-item"
            :class="{
                active:
                    route().current('patrols.index') ||
                    route().current('patrols.show'),
            }"
        >
            <Link :href="route('patrols.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-shield-check"></em
                ></span>
                <span class="nk-menu-text">Patrol</span>
            </Link>
        </li>

        <li
            class="nk-menu-item"
            :class="{
                active:
                    route().current('sos.index') || route().current('sos.show'),
            }"
        >
            <Link :href="route('sos.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-report"></em
                ></span>
                <span class="nk-menu-text">SOS / Incident</span>
            </Link>
        </li>
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">Setup</h6>
        </li>
        <li
            class="nk-menu-item"
            :class="{ active: route().current('companies.index') }"
        >
            <Link :href="route('companies.index')" class="nk-menu-link">
                <span class="nk-menu-icon">
                    <em class="icon ni ni-building"> </em
                ></span>
                <span class="nk-menu-text">Company</span>
            </Link>
        </li>
        <li
            class="nk-menu-item"
            :class="{ active: route().current('locations.index') }"
        >
            <Link :href="route('locations.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-location"></em
                ></span>
                <span class="nk-menu-text">Location</span>
            </Link>
        </li>
        <li
            class="nk-menu-item"
            :class="{ active: route().current('checkpoints.index') }"
        >
            <Link :href="route('checkpoints.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-map-pin"></em
                ></span>
                <span class="nk-menu-text">Checkpoint</span>
            </Link>
        </li>
        <li
            class="nk-menu-item"
            :class="{ active: route().current('guards.index') }"
        >
            <Link :href="route('guards.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-security"></em
                ></span>
                <span class="nk-menu-text">Guard</span>
            </Link>
        </li>
        <li
            class="nk-menu-item"
            :class="{ active: route().current('users.index') }"
        >
            <Link :href="route('users.index')" class="nk-menu-link">
                <span class="nk-menu-icon"
                    ><em class="icon ni ni-users"></em
                ></span>
                <span class="nk-menu-text">User</span>
            </Link>
        </li>
    </ul>
    <!-- /menu -->
</template>

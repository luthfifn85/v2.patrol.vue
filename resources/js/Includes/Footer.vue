<script setup>
import moment from "moment";

const appName = import.meta.env.VITE_APP_NAME || "DefaultAppName";
</script>
<template>
    <div class="nk-footer mt-0">
        <div class="container wide-xl">
            <div class="nk-footer-wrap g-2">
                <div class="nk-footer-copyright">
                    &copy; 2021 - {{ moment().format("YYYY") }} {{ appName }}.
                    All Rights Reserved.
                </div>
                <div class="nk-footer-links">
                    <ul class="nav nav-sm">
                        <li class="nav-item dropup">
                            <a
                                href="#"
                                class="dropdown-toggle dropdown-indicator has-indicator nav-link text-base"
                                data-bs-toggle="dropdown"
                                data-offset="0,10"
                                ><span>English</span></a
                            >
                            <div
                                class="dropdown-menu dropdown-menu-sm dropdown-menu-end"
                            >
                                <ul class="language-list">
                                    <li>
                                        <a href="#" class="language-item">
                                            <span class="language-name"
                                                >English</span
                                            >
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <slot name="script"></slot>
    </div>
</template>

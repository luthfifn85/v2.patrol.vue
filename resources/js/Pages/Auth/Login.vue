<script setup>
import LoginLayout from "@/Layouts/LoginLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Link, useForm } from "@inertiajs/vue3";
import moment from "moment";

defineProps({
    status: {
        type: String,
    },
    appName: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const handleSubmit = (e) => {
    e.preventDefault(); // Prevent default form submission
    form.post(route("login"), {
        onSuccess: () => {
            form.reset("password");
        },
        onError: (errors) => {
            form.reset("password");
            console.log("Errors:", errors); // For debugging
        },
    });
};
</script>

<template>
    <LoginLayout>
        <div class="nk-content">
            <div class="nk-block nk-block-middle nk-auth-body wide-xs">
                <div class="brand-logo pb-5 text-center">
                    <a href="#" class="logo-link">
                        <img
                            class="logo-light logo-img logo-img-lg"
                            src="/images/logo-dar.png"
                            alt="logo"
                        />
                        <img
                            class="logo-dark logo-img logo-img-lg"
                            src="/images/logo-dar.png"
                            alt="logo-dark"
                        />
                    </a>
                    <br />
                    <p>Mobile Apps by PT. Absolute Services</p>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner card-inner-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title">Welcome</h4>
                                <div class="nk-block-des">
                                    <p>
                                        Access {{ appName }}
                                        admin panel using your account.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form method="POST" @submit.prevent="handleSubmit">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="default-01">
                                        Email
                                    </label>
                                </div>
                                <div class="form-control-wrap">
                                    <input
                                        type="email"
                                        class="form-control form-control-lg"
                                        id="email"
                                        v-model="form.email"
                                        placeholder="Enter your email address"
                                        required
                                        autofocus
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="password"
                                        >Password</label
                                    >
                                </div>
                                <div class="form-control-wrap">
                                    <a
                                        tabindex="-1"
                                        href="#"
                                        class="form-icon form-icon-right passcode-switch lg"
                                        data-target="password"
                                    >
                                        <em
                                            class="passcode-icon icon-show icon ni ni-eye"
                                        ></em>
                                        <em
                                            class="passcode-icon icon-hide icon ni ni-eye-off"
                                        ></em>
                                    </a>
                                    <input
                                        type="password"
                                        class="form-control form-control-lg"
                                        id="password"
                                        v-model="form.password"
                                        placeholder="Enter your password"
                                        required
                                    />
                                    <InputError
                                        :message="form.errors.password"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <button
                                    class="btn btn-lg btn-dark btn-block"
                                    :disabled="form.processing"
                                >
                                    {{
                                        form.processing ? "Loading..." : "Login"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div
                class="nk-footer nk-auth-footer-full max-w-full !py-6"
                style="border-top: 1px solid #e0e0e0"
            >
                <div class="container wide-lg">
                    <div class="row g-3">
                        <div class="col-lg-6 order-lg-last">
                            <ul
                                class="nav nav-sm justify-content-center justify-content-lg-end"
                            >
                                <li class="nav-item">
                                    <Link
                                        class="link link-secondary fw-normal py-2 px-3"
                                        :href="route('terms_policy')"
                                    >
                                        Terms &amp; Policy
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <div
                                class="nk-block-content text-center text-lg-left"
                            >
                                <p class="text-soft">
                                    &copy; 2021 - {{ moment().format("YYYY") }}
                                    {{ appName }}. All Rights Reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-split-content nk-split-stretch bg-abstract"></div>
    </LoginLayout>
</template>

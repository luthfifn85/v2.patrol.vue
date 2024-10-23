<script setup>
import LoginLayout from '@/Layouts/LoginLayout.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String
    },
    appName: {
        type: String
    }
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <LoginLayout>
        <div class="nk-content ">
            <div class="nk-split nk-split-page nk-split-md">
                <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                    <div class="nk-block nk-block-middle nk-auth-body">
                        <div class="brand-logo pb-5">
                            <a href="#" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="images/logo.png" srcset="images/logo2x.png 2x" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" src="images/logo-dark.png" srcset="images/logo-dark2x.png 2x" alt="logo-dark">
                            </a>
                        </div>
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title">Login</h5>
                                <div class="nk-block-des">
                                    <p>Access {{ appName }} panel using your Account</p>
                                </div>
                            </div>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="email">Email</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="email" class="form-control form-control-lg" id="email" v-model="form.email" placeholder="Enter your email address" required autofocus>
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="form-control-wrap">
                                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                    </a>
                                    <input type="password" class="form-control form-control-lg" id="password" v-model="form.password" placeholder="Enter your password" required>
                                    <InputError :message="form.errors.password" />
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="nk-block nk-auth-footer">
                        <div class="nk-block-between">
                            <ul class="nav nav-sm">
                                <li class="nav-item">
                                    <a class="link link-primary fw-normal py-2 px-3" href="#">Terms & Policy</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-3">
                            <p>&copy; 2024 {{ appName }}. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
                <div class="nk-split-content nk-split-stretch bg-abstract"></div>
            </div>
        </div>
    </LoginLayout>
</template>

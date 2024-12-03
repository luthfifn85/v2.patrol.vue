<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<style lang="css">
.accordion-body.collapse.show .accordion-inner {
    visibility: visible !important;
}
</style>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-lg wide-md mt-4">
            <div class="nk-block-head-content">
                <div class="nk-block-head-sub"><span>FAQs</span></div>
                <h2 class="nk-block-title fw-normal">
                    Frequently Asked Questions
                </h2>
            </div>
        </div>
        <div class="nk-block">
            <div class="card">
                <div id="faqs" class="accordion">
                    <div class="accordion-item">
                        <a
                            href="#"
                            class="accordion-head"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq-q1"
                        >
                            <h6 class="title">What is PATROL?</h6>
                            <span class="accordion-icon"></span>
                        </a>
                        <div
                            class="accordion-body collapse show"
                            id="faq-q1"
                            data-bs-parent="#faqs"
                        >
                            <div class="accordion-inner">
                                <p>
                                    PATROL is a web application with various
                                    function to support end-to-end process for
                                    security services.
                                </p>
                                <p>
                                    The foundation — to work without sweating
                                    the small things.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <a
                            href="#"
                            class="accordion-head collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq-q2"
                        >
                            <h6 class="title">How to access PATROL?</h6>
                            <span class="accordion-icon"></span>
                        </a>
                        <div
                            class="accordion-body collapse"
                            id="faq-q2"
                            data-bs-parent="#faqs"
                        >
                            <div class="accordion-inner">
                                <p>
                                    Open your web browser application in your
                                    Computer / Tab / Mobile Phone like Google
                                    Chrome or Mozilla Firefox (We recommend
                                    using those two with the latest version) or
                                    go to this
                                    <a
                                        href="https://assist-app.id"
                                        target="_blank"
                                        class="text-danger"
                                        >link</a
                                    >.
                                </p>
                                <p>Login with your account then.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <a
                            href="#"
                            class="accordion-head collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq-q3"
                        >
                            <h6 class="title">
                                How can i get the User Account?
                            </h6>
                            <span class="accordion-icon"></span>
                        </a>
                        <div
                            class="accordion-body collapse"
                            id="faq-q3"
                            data-bs-parent="#faqs"
                        >
                            <div class="accordion-inner">
                                <p>
                                    You will automatically get the User Account
                                    if you already registered as an Employee at
                                    <a
                                        href="https://www.absolute-services.co.id"
                                        target="_blank"
                                        class="text-danger"
                                        >PT. Absolute Services</a
                                    >.
                                </p>
                                <p>
                                    For further information, contact your
                                    Superior or our Customer Support.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <a
                            href="#"
                            class="accordion-head collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#faq-q4"
                        >
                            <h6 class="title">
                                I forgot the User Account / Password?
                            </h6>
                            <span class="accordion-icon"></span>
                        </a>
                        <div
                            class="accordion-body collapse"
                            id="faq-q4"
                            data-bs-parent="#faqs"
                        >
                            <div class="accordion-inner">
                                <p>
                                    If you lost or do not remember both of your
                                    Password or your Registered Email Account,
                                    contact your Superior first.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="nk-help">
                        <div class="nk-help-img">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 120 118"
                            >
                                <path
                                    d="M8.916,94.745C-.318,79.153-2.164,58.569,2.382,40.578,7.155,21.69,19.045,9.451,35.162,4.32,46.609.676,58.716.331,70.456,1.845,84.683,3.68,99.57,8.694,108.892,21.408c10.03,13.679,12.071,34.71,10.747,52.054-1.173,15.359-7.441,27.489-19.231,34.494-10.689,6.351-22.92,8.733-34.715,10.331-16.181,2.192-34.195-.336-47.6-12.281A47.243,47.243,0,0,1,8.916,94.745Z"
                                    transform="translate(0 -1)"
                                    fill="#f6faff"
                                />
                                <rect
                                    x="18"
                                    y="32"
                                    width="84"
                                    height="50"
                                    rx="4"
                                    ry="4"
                                    fill="#fff"
                                />
                                <rect
                                    x="26"
                                    y="44"
                                    width="20"
                                    height="12"
                                    rx="1"
                                    ry="1"
                                    fill="#ffe5e6"
                                />
                                <rect
                                    x="74"
                                    y="44"
                                    width="20"
                                    height="12"
                                    rx="1"
                                    ry="1"
                                    fill="#ffe5e6"
                                />
                                <rect
                                    x="38"
                                    y="60"
                                    width="20"
                                    height="12"
                                    rx="1"
                                    ry="1"
                                    fill="#ffe5e6"
                                />
                                <rect
                                    x="50"
                                    y="44"
                                    width="20"
                                    height="12"
                                    rx="1"
                                    ry="1"
                                    fill="#ffe5e6"
                                />
                                <rect
                                    x="62"
                                    y="60"
                                    width="20"
                                    height="12"
                                    rx="1"
                                    ry="1"
                                    fill="#ffe5e6"
                                />
                                <path
                                    d="M98,32H22a5.006,5.006,0,0,0-5,5V79a5.006,5.006,0,0,0,5,5H52v8H45a2,2,0,0,0-2,2v4a2,2,0,0,0,2,2H73a2,2,0,0,0,2-2V94a2,2,0,0,0-2-2H66V84H98a5.006,5.006,0,0,0,5-5V37A5.006,5.006,0,0,0,98,32ZM73,94v4H45V94Zm-9-2H54V84H64Zm37-13a3,3,0,0,1-3,3H22a3,3,0,0,1-3-3V37a3,3,0,0,1,3-3H98a3,3,0,0,1,3,3Z"
                                    transform="translate(0 -1)"
                                    fill="#c61f25"
                                />
                                <path
                                    d="M61.444,41H40.111L33,48.143V19.7A3.632,3.632,0,0,1,36.556,16H61.444A3.632,3.632,0,0,1,65,19.7V37.3A3.632,3.632,0,0,1,61.444,41Z"
                                    transform="translate(0 -1)"
                                    fill="#c61f25"
                                />
                                <path
                                    d="M61.444,41H40.111L33,48.143V19.7A3.632,3.632,0,0,1,36.556,16H61.444A3.632,3.632,0,0,1,65,19.7V37.3A3.632,3.632,0,0,1,61.444,41Z"
                                    transform="translate(0 -1)"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-miterlimit="10"
                                    stroke-width="2"
                                />
                                <line
                                    x1="40"
                                    y1="22"
                                    x2="57"
                                    y2="22"
                                    fill="none"
                                    stroke="#fffffe"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                />
                                <line
                                    x1="40"
                                    y1="27"
                                    x2="57"
                                    y2="27"
                                    fill="none"
                                    stroke="#fffffe"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                />
                                <line
                                    x1="40"
                                    y1="32"
                                    x2="50"
                                    y2="32"
                                    fill="none"
                                    stroke="#fffffe"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                />
                                <line
                                    x1="30.5"
                                    y1="87.5"
                                    x2="30.5"
                                    y2="91.5"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <line
                                    x1="28.5"
                                    y1="89.5"
                                    x2="32.5"
                                    y2="89.5"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <line
                                    x1="79.5"
                                    y1="22.5"
                                    x2="79.5"
                                    y2="26.5"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <line
                                    x1="77.5"
                                    y1="24.5"
                                    x2="81.5"
                                    y2="24.5"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <circle
                                    cx="90.5"
                                    cy="97.5"
                                    r="3"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-miterlimit="10"
                                />
                                <circle
                                    cx="24"
                                    cy="23"
                                    r="2.5"
                                    fill="none"
                                    stroke="#c61f25"
                                    stroke-miterlimit="10"
                                />
                            </svg>
                        </div>
                        <div class="nk-help-text">
                            <h5>We’re here to help you!</h5>
                            <p class="text-soft">
                                Ask a question or report an issues. Our team
                                support team will get back to you by email.
                            </p>
                        </div>
                        <div class="nk-help-action">
                            <a
                                href="mailto:info@absolute-services.co.id"
                                class="btn btn-lg btn-outline-danger"
                                >Get Support Now</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

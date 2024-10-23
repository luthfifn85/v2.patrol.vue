<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    title: {
        type: String,
        required: true
    },
    get: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <AppLayout>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title mb-0">{{ title }}</h5>
                    <div class="nk-block-des text-soft">
                        <p>You have total <span class="text-base">{{ get.length }}</span>
                            data.</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                            data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3">
                                <li class="nk-block-tools-opt">
                                    <a href="#" data-target="addItem" class="toggle btn btn-icon btn-dark text-white d-md-none"><em class="icon ni ni-plus"></em></a>
                                    <a href="#" data-target="addItem" class="toggle btn btn-dark text-white d-none d-md-inline-flex"><em class="icon ni ni-plus"></em></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- datatable -->
        <div class="nk-block">
            <div class="card card-bordered card-preview">
                <div class="card-inner">
                    <table class="datatable-init-export nowrap table" data-export-title="Export">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="data in get" :key="data.id">
                                <td>{{ data.name }}</td>
                                <td>
                                    <span :class="data.is_active == 1 ? 'badge bg-success rounded-pill' : 'badge bg-danger rounded-pill'">
                                        {{ data.is_active == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <ul class="nk-tb-actions gx-1">
                                        <li><a href="#" class="btn btn-icon btn-trigger"><em class="icon ni ni-more-v"></em></a></li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /datatable -->

        <!-- add modal -->
        <div class="nk-add-product toggle-slide toggle-slide-right" data-content="addItem" data-toggle-screen="any"
            data-toggle-overlay="true" data-toggle-body="true" data-simplebar>
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title">New Client</h5>
                    <div class="nk-block-des">
                        <p class="mb-0">Add information for new client.</p>
                        <code class="text-danger">* Field required</code>
                    </div>
                </div>
            </div>
        </div>
        <!-- /add modal -->

    </AppLayout>
</template>

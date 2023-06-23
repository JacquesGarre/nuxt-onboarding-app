<template>
    <AppNavbar />
    <div class="container-fluid pt-0">
        <div class="page-header min-height-100 border-radius-xl mt-4"
            style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div
                        class="icon icon-shape icon-l shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-white text-dark">
                        <font-awesome-icon :icon="[currentPage.iconPrefix, currentPage.icon]" />
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $t(currentPage.label) }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $t(currentPage.description) }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item" v-for="tab in currentPage.tabs">
                                <NuxtLink class="nav-link mb-0 px-0 py-1" :to="tab.url">
                                    <span class="ms-1">{{ $t(tab.label) }}</span>
                                </NuxtLink>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
const { menu } = useMenu()
const menuArray = Object.values(menu.value);
const route = useRoute()
const currentPath = route.path;
const currentPage = menuArray.find(item => currentPath.includes(item.url));
</script>
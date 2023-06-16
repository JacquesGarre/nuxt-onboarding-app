<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm" v-for="crumb in crumbs">
                <a class="opacity-5 text-dark">Admin</a>
            </li>
            <li class="breadcrumb-item text-sm" v-for="crumb in crumbs">
                <NuxtLink :to="crumb.url" class="opacity-5 text-dark">{{ crumb.label }}</NuxtLink>
            </li>
        </ol>
        <h6 class="font-weight-bolder mb-0">{{ currentRoute }}</h6>
    </nav>
</template>
<script setup>

    import { useMenuStore } from '~/store/menu'

    const getBreadcrumbs = () => {

        const menuStore = useMenuStore()
        const menu = Array.from(menuStore.menu)
        const route = useRoute()
        const currentPath = route.path;
        const breadcrumbs = [];

        let currentItem = menu.find(item => item.url === currentPath);
        while (currentItem) {
            breadcrumbs.unshift(currentItem);
            const parentUrl = currentItem.url.slice(0, currentItem.url.lastIndexOf('/'));
            currentItem = menu.find(item => item.url === parentUrl);
        }
        return Array.from(breadcrumbs);

    }

    const crumbs = getBreadcrumbs()

    console.log(crumbs)

</script>
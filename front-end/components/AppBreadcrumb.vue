<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm" v-for="crumb in crumbs">
                <a class="opacity-5 text-dark">{{ organization.name }}</a>
            </li>
            <li class="breadcrumb-item text-sm" v-for="crumb in crumbs">
                <NuxtLink :to="crumb.url" class="opacity-9 text-dark font-weight-bold">{{ $t(crumb.label) }}</NuxtLink>
            </li>
        </ol>
    </nav>
</template>
<script setup>

    const { menu } = useMenu()
    const menuArray = Object.values(menu.value);
    const { organization } = useOrganization()

    const getBreadcrumbs = () => {
        const route = useRoute()
        const currentPath = route.path;
        const breadcrumbs = [];
        let currentItem = menuArray.find(item => item.url === currentPath);
        while (currentItem) {
            breadcrumbs.unshift(currentItem);
            const parentUrl = currentItem.url.slice(0, currentItem.url.lastIndexOf('/'));
            currentItem = menuArray.find(item => item.url === parentUrl);
        }
        return Array.from(breadcrumbs);
    }
    const crumbs = getBreadcrumbs()

</script>
<template>
    <AppBreadcrumb/>
    <h1>{{ currentMenu.label }}</h1>
</template>
<script setup>
    import { useMenuStore } from '~/store/menu'
    const route = useRoute()
    const menuStore = useMenuStore()
    const menu = Array.from(menuStore.menu)
    const currentPath = route.path;
    const currentMenu = menu.find(item => item.url === currentPath);
</script>
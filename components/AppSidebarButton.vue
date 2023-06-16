<template>
    <NuxtLink :to="menuItem.url" class="nav-link">
        <div
            class="icon icon-shape icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-white text-dark">
            <font-awesome-icon :icon="['fas', menuItem.icon]"/>
        </div>
        <span class="nav-link-text ms-1">{{ $t(menuItem.label) }}</span>
    </NuxtLink>
</template>
<script setup>
    const props = defineProps({
        menuItem: Object
    })
</script>



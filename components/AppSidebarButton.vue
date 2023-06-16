<template>
    <NuxtLink :to="menuItem.url" class="nav-link">
        <div
            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <font-awesome-icon :icon="['fas', menuItem.icon]" class="text-dark" />
        </div>
        <span class="nav-link-text ms-1">{{ menuItem.label }}</span>
    </NuxtLink>
</template>
<script setup>
    const route = useRoute()
    const props = defineProps({
        menuItem: Object
    })
    const active = route.path.includes(props.menuItem.url)
</script>



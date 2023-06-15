<template>
    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 mt-3">{{ menuItem.label }}</h6>
</template>
<script setup>
    const props = defineProps({
        menuItem: Object
    })
</script>
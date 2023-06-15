<template>
    <h1>Organization</h1>
</template>
<script>
    import { useMenuStore } from '~/store/menu'
    export default {
        setup() {
            const route = useRoute()
            const menuStore = useMenuStore()
            menuStore.setCurrentRoute(route.path)
        }
    }
</script>
<template>
    <div class="g-sidenav-show bg-gray-100 admin-panel">
        <AppSidebar />
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <NuxtPage />
        </main>
        <ModalsContainer />
    </div>
</template>
<script>
    import { ModalsContainer } from 'vue-final-modal'
</script>

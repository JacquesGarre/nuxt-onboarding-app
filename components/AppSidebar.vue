<template>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <NuxtLink to="/admin/dashboard" class="navbar-brand m-0 text-center p-1">
                <span class="font-weight-bold fs-4">{{ organization.name }}</span><br>
                <i class="opacity-5 fs-7">{{ $t('poweredBy') }} OnboardR</i>
            </NuxtLink>
            
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item" v-for="menuItem in menu">
                    <AppSidebarButton :menuItem=menuItem v-if="(menuItem.type == 'button')" />
                    <AppSidebarSeparator :menuItem=menuItem v-if="(menuItem.type == 'separator')" />
                </li>
            </ul>
        </div>
    </aside>
</template>
<script setup>
    const { menu } = useMenu()
    const { organization } = useOrganization()
</script>
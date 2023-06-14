<template>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <NuxtLink to="/admin" class="navbar-brand m-0 text-center text-uppercase">
        <span class="font-weight-bold">Onboarding App</span>
      </NuxtLink>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <AppSidebarButton />
        </li>
        <li class="nav-item">
          <AppSidebarButton />
        </li>
        <li class="nav-item">
          <AppSidebarButton />
        </li>
        <li class="nav-item mt-3">
          <AppSidebarSeparator />
        </li>
        <li class="nav-item">
          <AppSidebarButton />
        </li>
        <li class="nav-item">
          <AppSidebarButton />
        </li>
      </ul>
    </div>
  </aside>
</template>
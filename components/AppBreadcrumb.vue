<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm" v-for="crumb in crumbs">
                <NuxtLink :to="crumb.to" class="opacity-5 text-dark">{{ crumb.title }} ({{ currentRoute }})</NuxtLink>
            </li>
        </ol>
        <h6 class="font-weight-bolder mb-0">{{ currentRoute }}</h6>
    </nav>
</template>
<script setup>

    import { useMenuStore } from '~/store/menu'


    const menuStore = useMenuStore()
    const menu = Array.from(menuStore.menu)

    const currentRoute = menuStore.currentRoute.toString()


    console.log('CURRENT ROUTE : ', currentRoute)

    const route = useRoute()
    const currentMenu = menu.filter(item => { return route.path.includes(item.url) })
    console.log('currentMenu',currentMenu)
    // console.log(currentMenu);

    const getBreadcrumbs = () => {
        const route = useRoute()
        const pathArray = route.path.split('/')
        pathArray.shift()
        const breadcrumbs = pathArray.reduce((breadcrumbArray, path, idx) => {
            if(path == 'admin'){
                breadcrumbArray.push({
                    to: '',
                    title: 'Admin',
                })
            } else {
                const url = !!breadcrumbArray[idx - 1]
                        ? breadcrumbArray[idx - 1].to + '/' + path
                        : '/' + path                    
                breadcrumbArray.push({
                    to: url,
                    title: 'yoo',
                })
            }
            return breadcrumbArray
        }, [])
        return breadcrumbs
    }

    const crumbs = getBreadcrumbs()

    console.log(crumbs)

</script>
import { defineStore } from 'pinia'

export const useMenuStore = defineStore('menuStore', {
    state: () => ({
        currentRoute: '',
        menu: [{
            type: 'button',
            label: 'Dashboard',
            url: '/admin/dashboard',
            active: true,
        },
        {
            type: 'separator',
            label: 'ORGANIZATION',
        },
        {
            type: 'button',
            label: 'My Organization',
            url: '/admin/organization',
        },
        {
            type: 'button',
            label: 'People',
            url: '/admin/people',
        },
        {
            type: 'button',
            label: 'Roles',
            url: '/admin/roles',
        },
        {
            type: 'separator',
            label: 'ON BOARDING',
        }] 
    }),
    actions: {
        setMenu(newMenu) {
            this.menu = newMenu
        },
        setCurrentRoute(newRoute) {
            this.currentRoute = newRoute
        }
    },
    getters: {
        getCurrentRoute() {
            return this.currentRoute
        }
    },
})




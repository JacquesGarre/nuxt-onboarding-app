import { defineStore } from 'pinia'

export const useMenuStore = defineStore('menuStore', {
    state: () => ({
        currentRoute: '',
        menu: [
        {
            type: 'separator',
            label: 'ORGANIZATION',
        },
        {
            type: 'button',
            label: 'My Organization',
            url: '/admin/organization',
            icon: 'building-user'
        },
        {
            type: 'button',
            label: 'People',
            url: '/admin/people',
            icon: 'users'
        },
        {
            type: 'button',
            label: 'Roles',
            url: '/admin/roles',
            icon: 'address-card'
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




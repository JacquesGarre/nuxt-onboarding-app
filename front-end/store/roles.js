import { defineStore } from 'pinia';

export const useRoleStore = defineStore('role', {

    state: () => ({
        roles: [
            {
                id:1,
                name:'Manager',
                class:'primary',
                rights: {
                    organization: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    },
                    people: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    },
                    roles: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    },
                    processes: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    },
                    onboarding: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    },
                    inventory: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    },
                    softwareLicences: {
                        create: 0,
                        read: 0,
                        update: 0,
                        delete: 0,
                    }
                }
            },
            {
                id:2,
                name:'HR',
                class:'danger',
                rights: {
                    organization: {
                        create: 0,
                        read: 1,
                        update: 0,
                        delete: 0,
                    },
                    people: {
                        create: 0,
                        read: 1,
                        update: 0,
                        delete: 1,
                    },
                    roles: {
                        create: 1,
                        read: 0,
                        update: 1,
                        delete: 0,
                    },
                    processes: {
                        create: 0,
                        read: 0,
                        update: 1,
                        delete: 1,
                    },
                    onboarding: {
                        create: 0,
                        read: 1,
                        update: 1,
                        delete: 0,
                    },
                    inventory: {
                        create: 0,
                        read: 1,
                        update: 0,
                        delete: 0,
                    },
                    softwareLicences: {
                        create: 0,
                        read: 1,
                        update: 0,
                        delete: 0,
                    }
                }
            }
        ]
    }),

    actions: {
        updateRoleData(data) {
            const role = this.roles.find(role => role.id == data.id);
            role.name = data.name;
            role.class = data.class;
        },
        removeRole(data) {
            this.roles = this.roles.filter(role => role.id != data.id);
        },
    },
    
});
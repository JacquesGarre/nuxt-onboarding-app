import { defineStore } from 'pinia';

export const useOrganizationStore = defineStore('organization', {

    state: () => ({
        organization: {
            name: 'Company name',
            description: 'Our enterprise is the best!',
            address: '69, Rue de la paix',
            postalCode: '75001',
            city: 'Paris',
            country: 'France',
            users: [
                {
                    id:1,
                    picture: '/assets/img/team-2.jpg',
                    firstname: 'John',
                    lastname: 'Michael',
                    email: 'yoyoyo@gmail.com',
                    isAdmin:0,
                    roles: ['Developer', 'IT'],
                    joinedOn: '18/05/2022'
                },
                {
                    id:2,
                    picture: '/assets/img/team-3.jpg',
                    firstname: 'Bobby',
                    lastname: 'La pointe',
                    email: 'yascascascyo@gmail.com',
                    isAdmin:1,
                    roles: ['Manager', 'HR'],
                    joinedOn: '23/04/2022'
                }
            ]
        }
    }),

    actions: {
        updateOrganizationData(data) {
            this.organization.name = data.name;
            this.organization.description = data.description;
            this.organization.address = data.address;
            this.organization.city = data.city;
            this.organization.postalCode = data.postalCode;
            this.organization.country = data.country;
        },
        removeAdmin(id) {
            const user = this.organization.users.find(user => user.id == id);
            user.isAdmin = 0;
            console.log(this.organization.users)
        },
        addAdmin(data) {
            const user = this.organization.users.find(user => user.id == data.user);
            user.isAdmin = 1;
        }
    },
    
});
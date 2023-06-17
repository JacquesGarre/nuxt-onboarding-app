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
            admins: [
                {
                    picture: '/assets/img/team-2.jpg',
                    firstname: 'John',
                    lastname: 'Michael',
                    email: 'yoyoyo@gmail.com',
                    roles: ['Developer', 'IT', 'Admin'],
                    joinedOn: '18/05/2022'
                },
                {
                    picture: '/assets/img/team-3.jpg',
                    firstname: 'Bobby',
                    lastname: 'La pointe',
                    email: 'yascascascyo@gmail.com',
                    roles: ['Manager', 'HR', 'Admin'],
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
    },
    
});
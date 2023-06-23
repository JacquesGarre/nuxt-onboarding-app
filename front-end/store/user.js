import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {

    state: () => ({
        users: [
            {
                id:1,
                picture: '/assets/img/team-2.jpg',
                firstname: 'John',
                lastname: 'Michael',
                email: 'yoyoyo@gmail.com',
                isAdmin:1,
                roles: [1, 2],
                joinedOn: '18/05/2022'
            },
            {
                id:2,
                picture: '/assets/img/team-3.jpg',
                firstname: 'Bobby',
                lastname: 'La pointe',
                email: 'yascascascyo@gmail.com',
                isAdmin:1,
                roles: [1],
                joinedOn: '23/04/2022'
            },
            {
                id:3,
                picture: '/assets/img/team-1.jpg',
                firstname: 'Roberto',
                lastname: 'Iglesios',
                email: 'asdasdasco@gmail.com',
                isAdmin:1,
                roles: [2],
                joinedOn: '23/04/2022'
            }
        ]
    }),

    actions: {
        createUser(data) {

        },
        deleteUser(id) {
 
        },
        editUser(data) {
   
        }
    },
    
});
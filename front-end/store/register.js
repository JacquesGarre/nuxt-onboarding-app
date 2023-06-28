import { defineStore } from 'pinia';


export const useRegisterStore = defineStore('register', {

    state: () => ({
        registered: false,
        user: {
            email: '',
            password: ''
        }
    }),

    actions: {
        setRegister(bool) {
            this.registered = bool;
        },
        setUser(user) {
            this.user.email = user.email;
            this.user.password = user.password;
        },
    },
    
});
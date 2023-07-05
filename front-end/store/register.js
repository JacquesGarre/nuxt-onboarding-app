import { defineStore } from 'pinia';


export const useRegisterStore = defineStore('register', {

    state: () => ({
        registered: false,
        user: {
            email: 'ascasc66asca6ccc@ascaascasccccsc.com', 
            password: 'Test1234'
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
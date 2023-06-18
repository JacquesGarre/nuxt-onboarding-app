import { defineStore } from 'pinia';

import organizationEditionForm from '../forms/organization_edit.json'

export const useFormStore = defineStore('form', {

    state: () => ({
        'organizationEditionForm': organizationEditionForm
    }),

    actions: {},
    
});
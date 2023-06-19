import { defineStore } from 'pinia';

import organizationEditionForm from '../forms/organization_edit.json'
import administratorAddForm from '../forms/administrator_add.json'

export const useFormStore = defineStore('form', {

    state: () => ({
        'organizationEditionForm': organizationEditionForm,
        'administratorAddForm': administratorAddForm
    }),

    actions: {},
    
});
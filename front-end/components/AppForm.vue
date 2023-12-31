<template>
    <form @submit.prevent="">
        <div class="row form-row" v-for="row in form.formSettings.display">
            <div class="form-group align-content-start align-content-start flex-column align-items-start" v-for="fieldID in row" :class="form.formSettings.fields[fieldID].class">

                <label :for="form.formSettings.fields[fieldID].id" class="float-start">{{ $t(form.formSettings.fields[fieldID].label) }}</label>

                <input v-if="form.formSettings.fields[fieldID].type == 'text'" 
                    type="text" 
                    class="form-control mb-1" 
                    :id="form.formSettings.fields[fieldID].id"
                    @keydown="showSubmit = true" 
                    v-model="model[fieldID]"
                    :placeholder="$t(form.formSettings.fields[fieldID].placeholder)"
                    :class="{ 'is-invalid': v$.model[fieldID].$errors.length, 'hasbeenfocused':v$.model[fieldID].$dirty }"
                >
                <textarea v-if="form.formSettings.fields[fieldID].type == 'textarea'"
                    class="form-control mb-1" 
                    :id="form.formSettings.fields[fieldID].id"
                    :rows="form.formSettings.fields[fieldID].rows"
                    @keydown="showSubmit = true"
                    v-model="model[fieldID]"
                    :placeholder="$t(form.formSettings.fields[fieldID].placeholder)"
                    :class="{ 'is-invalid': v$.model[fieldID].$errors.length, 'hasbeenfocused':v$.model[fieldID].$dirty }"
                ></textarea>

                <select v-if="form.formSettings.fields[fieldID].type == 'select'" 
                    class="form-control mb-1" 
                    :id="form.formSettings.fields[fieldID].id"
                    @keydown="showSubmit = true" 
                    v-model="model[fieldID]"
                    :placeholder="$t(form.formSettings.fields[fieldID].placeholder)"
                    :class="{ 'is-invalid': v$.model[fieldID].$errors.length, 'hasbeenfocused':v$.model[fieldID].$dirty }"
                >
                    <option v-for="(item, index) in lists[form.formSettings.fields[fieldID].list]"
                        :value="item[form.formSettings.fields[fieldID].listPattern.value]" :selected="index == 0 ? 'selected' : false" v-if="form.formSettings.fields[fieldID].listType == 'dynamic'">
                        {{ form.formSettings.fields[fieldID].listPattern.label.map(field => item[field]).join(' ')}} 
                    </option>
                    <option v-for="(item, index) in lists[form.formSettings.fields[fieldID].list]"
                        :value="item[form.formSettings.fields[fieldID].listPattern.value]" :selected="index == 0 ? 'selected' : false" v-if="form.formSettings.fields[fieldID].listType == 'static'">
                        {{ $t( item[form.formSettings.fields[fieldID].listPattern.label] ) }} 
                    </option>
                </select>

                <div class="invalid-feedback" v-for="error of v$.model[fieldID].$errors" :key="error.$uid">
                    {{ error.$message }}
                </div>
            </div>
        </div>
        <button type="submit" class="btn bg-gradient-dark mb-0 mt-3" v-show="showSubmit" @click="submitForm">{{ $t(form.formSettings.btn.label) }}</button>
    </form>
</template>
<script>

import { useI18n } from "vue-i18n";
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, helpers } from '@vuelidate/validators'
import { useForm } from "~/composables/useForm";
import { useOrganization } from "~/composables/useOrganization"
import classes from '../data/classes.json'

export default {
    props: ['id', 'values', 'action', 'inModal', 'closeModal'],
    setup(props) {
        const i18n = useI18n()
        const form = useForm(props.id)
        const settings = JSON.parse(JSON.stringify(form))
        return {
            v$: useVuelidate(),
            data: props.values ?? {}, 
            form: form,
            showSubmit: !settings.formSettings.btn.hideWhenUntouched,
            i18n: i18n,
            submitAction: props.action,
            lists: {
                users: useOrganization().organization.users,
                admins: useOrganization().organization.users.filter((user) => user.isAdmin == 1),
                nonAdminUsers: useOrganization().organization.users.filter((user) => user.isAdmin == 0),
                classes: classes
            }
        }
    },
    data() {
        return {
           model: JSON.parse(JSON.stringify(this.data))
        }
    },
    validations() {

        let validation = {};
        const form = JSON.parse(JSON.stringify(this.form))
        for(const fieldID in form.formSettings.validations){
            const field = form.formSettings.fields[fieldID]
            const constraints = form.formSettings.validations[field.id]
            validation[field.id] = {
                $autoDirty: true
            }
            for(const constraint in constraints){
                switch(constraint){
                    case 'minLength':
                        validation[field.id][constraint] = helpers.withMessage(
                            this.i18n.t(
                                'minLengthError', 
                                {
                                    fieldLabel: this.i18n.t(field.label),
                                    length: constraints[constraint]
                                }
                            ), 
                            minLength(constraints[constraint])
                        )
                    break;
                    case 'required':
                        validation[field.id][constraint] = helpers.withMessage(
                            this.i18n.t(
                                'fieldRequiredError', 
                                {
                                    fieldLabel: this.i18n.t(field.label)
                                }
                            ), 
                            required
                        )
                    break;
                }
            }
        }
        return {
            model: validation
        }
    },
    methods: {
        async submitForm () {
           
            const isFormCorrect = await this.v$.$validate()
            if (isFormCorrect) {
                this.submitAction(this.model)
                this.showSubmit = false
                this.v$.$reset()
               
                if(this.inModal){
                    this.closeModal();
                }
            }
        }
    }
}
</script>
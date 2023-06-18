<template>
    <form @submit.prevent="">
        <div class="row form-row" v-for="row in form.formSettings.display">
            <div class="form-group align-content-start" v-for="fieldID in row" :class="form.formSettings.fields[fieldID].class">
                <label :for="form.formSettings.fields[fieldID].id">{{ $t(form.formSettings.fields[fieldID].label) }}</label>
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
                <div class="invalid-feedback" v-for="error of v$.model[fieldID].$errors" :key="error.$uid">
                    {{ error.$message }}
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3" v-show="showSubmit" @click="submitForm">{{ $t('saveChangesBtn') }}</button>
    </form>
</template>
<script>

import { useI18n } from "vue-i18n";
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, helpers } from '@vuelidate/validators'
import { useForm } from "~/composables/useForm";

export default {
    props: ['id', 'model', 'action'],
    setup(props) {
        const i18n = useI18n()
        return {
            v$: useVuelidate(),
            data: props.model, 
            form: useForm(props.id),
            showSubmit: false,
            i18n: i18n,
            submitAction: props.action
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
            }
        }
    }
}
</script>
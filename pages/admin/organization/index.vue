<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card h-100">
                    <div class="card-header pb-0 p-5">
                        <h6 class="mb-0">Information</h6>
                    </div>
                    <div class="card-body p-5 pt-4">
                        <form @submit.prevent="submitForm">
                            <div class="row form-row">
                                <div class="col-6 form-group">

                                    <label for="name">{{ $t('organizationInformationNameInputLabel') }}</label>
                                    <input type="text" class="form-control mb-1" id="name" 
                                        v-model="name"
                                        :placeholder="$t('organizationInformationNameInputPlaceholder')"
                                        :class="{ 'is-invalid': v$.name.$errors.length, 'is-valid': !v$.name.$errors.length && v$.name.$dirty }"
                                    >
                                    <div class="invalid-feedback" v-for="error of v$.name.$errors" :key="error.$uid">
                                        {{ error.$message }}
                                    </div>

                                    <label for="description">{{
                                        $t('organizationInformationDescriptionInputLabel') }}</label>
                                    <textarea class="form-control mb-1" rows="5" id="description"
                                        v-model="description"
                                        :placeholder="$t('organizationInformationDescriptionInputPlaceholder')"
                                        :class="{ 'is-invalid': v$.description.$errors.length, 'is-valid': !v$.description.$errors.length && v$.description.$dirty }"
                                    ></textarea>
                                </div>
                                <div class="col-6 form-group row">
                                    <div class="col-12">
                                        <label for="address">{{ $t('organizationInformationAddressInputLabel')
                                        }}</label>
                                        <input type="text" class="form-control mb-1" id="address"
                                            v-model="address"
                                            :placeholder="$t('organizationInformationAddressInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.address.$errors.length, 'is-valid': !v$.address.$errors.length && v$.address.$dirty }"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <label for="city">{{ $t('organizationInformationCityInputLabel')
                                        }}</label>
                                        <input type="text" class="form-control mb-1" id="city"
                                            v-model="city"
                                            :placeholder="$t('organizationInformationCityInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.city.$errors.length, 'is-valid': !v$.city.$errors.length && v$.city.$dirty }"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <label for="postalCode">{{
                                            $t('organizationInformationPostalCodeInputLabel') }}</label>
                                        <input type="text" class="form-control mb-1" id="postalCode"
                                            v-model="postalCode"
                                            :placeholder="$t('organizationInformationPostalCodeInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.postalCode.$errors.length, 'is-valid': !v$.postalCode.$errors.length && v$.postalCode.$dirty }"
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label for="country">{{ $t('organizationInformationCountryInputLabel')
                                        }}</label>
                                        <input type="text" class="form-control mb-1" id="country"
                                            :model="country"
                                            :placeholder="$t('organizationInformationCountryInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.postalCode.$errors.length, 'is-valid': !v$.postalCode.$errors.length && v$.postalCode.$dirty }"
                                        >
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">{{ $t('submitBtn') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import { useI18n } from "vue-i18n";
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, helpers } from '@vuelidate/validators'


export default {
    setup() {
        return {
            v$: useVuelidate()
        }
    },
    data() {
        return {
            name: 'Company name',
            description: '',
            address: '',
            postalCode: '',
            city: '',
            country: ''
        }
    },
    validations() {

        const i18n = useI18n()

        return {
            name: {
                minLength: helpers.withMessage(
                    i18n.t(
                        'minLengthError', 
                        {
                            fieldLabel: i18n.t('organizationInformationNameInputLabel'),
                            length: 3
                        }
                    ), 
                    minLength(3)
                ),
                required: helpers.withMessage(
                    i18n.t(
                        'fieldRequiredError', 
                        {
                            fieldLabel: i18n.t('organizationInformationNameInputLabel')
                        }
                    ), 
                    required
                ),
                $autoDirty: true
            },
            description: {},
            address: {},
            postalCode: {},
            city: {},
            country: {}
        }
    },
    methods: {
        async submitForm () {
            const isFormCorrect = await this.v$.$validate()
            if (isFormCorrect) {
                alert('Saving data')
            } else {
                alert('Errors')
            }
        }
    }
}
</script>
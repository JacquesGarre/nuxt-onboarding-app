<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Information</h6>
                    </div>
                    <div class="card-body pt-4">
                        <form @submit.prevent="">
                            <div class="row form-row">
                                <div class="col-6 form-group align-content-start">
                                    <label for="name">{{ $t('organizationInformationNameInputLabel') }}</label>
                                    <input type="text" class="form-control mb-1" id="name"
                                        @keydown="showSubmit = true" 
                                        v-model="organizationData.name"
                                        :placeholder="$t('organizationInformationNameInputPlaceholder')"
                                        :class="{ 'is-invalid': v$.organizationData.name.$errors.length, 'hasbeenfocused':v$.organizationData.name.$dirty }"
                                    >
                                    <div class="invalid-feedback" v-for="error of v$.organizationData.name.$errors" :key="error.$uid">
                                        {{ error.$message }}
                                    </div>

                                    <label for="description">{{
                                        $t('organizationInformationDescriptionInputLabel') }}</label>
                                    <textarea class="form-control mb-1" rows="1" id="description"
                                        @keydown="showSubmit = true"
                                        v-model="organizationData.description"
                                        :placeholder="$t('organizationInformationDescriptionInputPlaceholder')"
                                        :class="{ 'is-invalid': v$.organizationData.description.$errors.length, 'hasbeenfocused':v$.organizationData.description.$dirty }"
                                    ></textarea>
                                </div>
                                <div class="col-6 form-group row align-content-start">
                                    <div class="col-6">
                                        <label for="address">{{ $t('organizationInformationAddressInputLabel')
                                        }}</label>
                                        <input type="text" class="form-control mb-1" id="address"
                                            @keydown="showSubmit = true"
                                            v-model="organizationData.address"
                                            :placeholder="$t('organizationInformationAddressInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.organizationData.address.$errors.length, 'hasbeenfocused':v$.organizationData.address.$dirty }"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <label for="city">{{ $t('organizationInformationCityInputLabel')
                                        }}</label>
                                        <input type="text" class="form-control mb-1" id="city"
                                            @keydown="showSubmit = true"
                                            v-model="organizationData.city"
                                            :placeholder="$t('organizationInformationCityInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.organizationData.city.$errors.length, 'hasbeenfocused':v$.organizationData.city.$dirty }"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <label for="postalCode">{{
                                            $t('organizationInformationPostalCodeInputLabel') }}</label>
                                        <input type="text" class="form-control mb-1" id="postalCode"
                                            @keydown="showSubmit = true"
                                            v-model="organizationData.postalCode"
                                            :placeholder="$t('organizationInformationPostalCodeInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.organizationData.postalCode.$errors.length, 'hasbeenfocused':v$.organizationData.postalCode.$dirty }"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <label for="country">{{ $t('organizationInformationCountryInputLabel')
                                        }}</label>
                                        <input type="text" class="form-control mb-1" id="country"
                                            @keydown="showSubmit = true"
                                            v-model="organizationData.country"
                                            :placeholder="$t('organizationInformationCountryInputPlaceholder')"
                                            :class="{ 'is-invalid': v$.organizationData.country.$errors.length, 'hasbeenfocused':v$.organizationData.country.$dirty }"
                                        >
                                    </div>
                                </div>
                            </div> 
                            <button type="submit" class="btn btn-primary mt-3" v-show="showSubmit" @click="submitForm">{{ $t('saveChangesBtn') }}</button>
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
        const { organization, updateOrganizationData } = useOrganization()
        return {
            v$: useVuelidate(),
            organization: organization, 
            showSubmit: false,
            updateOrganizationData: updateOrganizationData
        }
    },
    data() {
        return {
            organizationData: {
                name: this.organization.name,
                description: this.organization.description,
                address: this.organization.address,
                postalCode: this.organization.postalCode,
                city: this.organization.city,
                country: this.organization.country
            }
        }

    },
    validations() {
        const i18n = useI18n()
        return {
            organizationData: {
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
                description: {
                    $autoDirty: true
                },
                address: {
                    $autoDirty: true
                },
                postalCode: {
                    $autoDirty: true
                },
                city: {
                    $autoDirty: true
                },
                country: {
                    $autoDirty: true
                }
            }
        }
    },
    methods: {
        async submitForm () {
            const isFormCorrect = await this.v$.$validate()
            if (isFormCorrect) {
                this.updateOrganizationData(this.organizationData)
                this.showSubmit = false
                this.v$.$reset()
            } else {
                alert('Errors')
            }
        }
    }
}
</script>
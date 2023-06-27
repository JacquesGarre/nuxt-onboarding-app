<template>
    <form role="form text-left" @submit.prevent="registerUser">
        <h6 class="mb-3">{{ $t('yourWorkspace') }}</h6>
        <div class="mb-4">
            <input 
                type="text" 
                class="form-control" 
                :placeholder="$t('organizationName')" 
                :aria-label="$t('organizationName')" 
                aria-describedby="email-addon"
                :class="{ 'is-invalid': v$.organization.name.$errors.length, 'hasbeenfocused':v$.organization.name.$dirty }"
                v-model="organization.name">
                <div class="invalid-feedback" v-for="error of v$.organization.name.$errors" :key="error.$uid">
                    {{ error.$message }}
                </div>
        </div>
        <h6 class="mb-3">{{ $t('yourData') }}</h6>
        <div class="row">
            <div class="mb-1 col-6">
                <input 
                    type="text" 
                    class="form-control" 
                    :placeholder="$t('firstName')" 
                    :aria-label="$t('firstName')" 
                    aria-describedby="email-addon"
                    :class="{ 'is-invalid': v$.user.firstName.$errors.length, 'hasbeenfocused':v$.user.firstName.$dirty }"
                    v-model="user.firstName">
                    <div class="invalid-feedback" v-for="error of v$.user.firstName.$errors" :key="error.$uid">
                        {{ error.$message }}
                    </div>
            </div>
            <div class="mb-1 col-6">
                <input 
                    type="text" 
                    class="form-control" 
                    :placeholder="$t('lastName')" 
                    :aria-label="$t('lastName')" 
                    aria-describedby="email-addon"
                    :class="{ 'is-invalid': v$.user.lastName.$errors.length, 'hasbeenfocused':v$.user.lastName.$dirty }"
                    v-model="user.lastName">
                    <div class="invalid-feedback" v-for="error of v$.user.lastName.$errors" :key="error.$uid">
                        {{ error.$message }}
                    </div>
            </div>
        </div>

        <div class="mb-3">
            <input 
                type="email" 
                class="form-control" 
                :placeholder="$t('email')" 
                :aria-label="$t('email')" 
                aria-describedby="email-addon"
                :class="{ 'is-invalid': v$.user.email.$errors.length, 'hasbeenfocused':v$.user.email.$dirty }"
                v-model="user.email">
                <div class="invalid-feedback" v-for="error of v$.user.email.$errors" :key="error.$uid">
                    {{ error.$message }}
                </div>
        </div>
        <div class="mb-3">
            <input 
                type="password" 
                class="form-control" 
                :placeholder="$t('password')" 
                :aria-label="$t('password')"
                aria-describedby="password-addon" 
                :class="{ 'is-invalid': v$.user.password.$errors.length, 'hasbeenfocused':v$.user.password.$dirty }"
                v-model="user.password">
                <div class="invalid-feedback" v-for="error of v$.user.password.$errors" :key="error.$uid">
                    {{ error.$message }}
                </div>
        </div>
        <div class="mb-3">
            <input 
                type="password" 
                class="form-control" 
                :placeholder="$t('passwordConfirmation')" 
                :aria-label="$t('passwordConfirmation')"
                aria-describedby="password-addon" 
                :class="{ 'is-invalid': v$.confirmPassword.$errors.length, 'hasbeenfocused':v$.confirmPassword.$dirty }"
                v-model="confirmPassword">
                <div class="invalid-feedback" v-for="error of v$.confirmPassword.$errors" :key="error.$uid">
                    {{ error.$message }}
                </div>
        </div>
        <h6 class="mb-3">{{ $t('yourLanguage') }}</h6>
        <div class="mb-3">
            <select class="form-select" aria-label="Language" v-model="user.defaultLang" id="lang-select">
                <option value="fr">{{ $t('fr') }}</option>
                <option value="en">{{ $t('en') }}</option>
                <option value="es">{{ $t('es') }}</option>
                <option value="it">{{ $t('it') }}</option>
            </select>
        </div>
        <div class="form-check form-switch text-left mt-5">
            <input 
                class="form-check-input" 
                type="checkbox" 
                id="flexCheckDefault" 
                v-model="checkedConditions"
                :class="{ 'is-invalid': v$.checkedConditions.$errors.length, 'hasbeenfocused':v$.checkedConditions.$dirty }"
               >
            <label class="form-check-label" for="flexCheckDefault">
                {{ $t('iAgreeThe')  }} <a href="javascript:;" class="text-dark font-weight-bolder">{{ $t('termsAndConditions')  }}</a>
            </label>
            <div class="invalid-feedback" v-for="error of v$.checkedConditions.$errors" :key="error.$uid">
                {{ error.$message }}
            </div>
        </div>
        <div class="text-center mt-1">
            <button type="button" class="btn bg-gradient-dark w-100 my-4 mb-2" @click="registerUser" :class="{ disabled: processing }">
                <span v-if="!processing">{{ $t('signUp') }}</span>
                <span v-if="processing">{{ $t('signingUp') }}</span>
            </button>
        </div>
        <div class="alert alert-danger text-white text-center mt-2" role="alert" v-if="error">
            {{ $t(error) }}
        </div>
    </form>
</template>
<script>

import { useI18n } from "vue-i18n";
import { useVuelidate } from '@vuelidate/core'
import { required, helpers, email, sameAs, minLength } from '@vuelidate/validators'

export default {
    setup() {
        return {
            v$: useVuelidate(),
            i18n: useI18n(),
        }
    },
    data() {
        return {
            checkedConditions: false,
            user: {
                firstName: '',
                lastName: '',
                email: '',
                password: '',
                defaultLang: 'en',
                admin: true
            },
            organization: {
                name: '',
            },
            confirmPassword: '',
            error: null,
            processing: false
        };
    },
    validations() {
        return {
            checkedConditions: { 
                shouldBeChecked: helpers.withMessage(
                    this.i18n.t('acceptingTermsAndConditions'), 
                    sameAs(true)
                )
            },
            confirmPassword: {
                required: helpers.withMessage(
                    this.i18n.t(
                        'fieldRequiredError', 
                        {
                            fieldLabel: this.i18n.t('passwordConfirmation')
                        }
                    ), 
                    required
                ),
                sameAs: helpers.withMessage(
                    this.i18n.t(
                        'mustBeSameThanError', 
                        {
                            field1: this.i18n.t('passwordConfirmation'),
                            field2: this.i18n.t('password')
                        }
                    ), 
                    sameAs(this.user.password)
                )
            },
            organization: {
                name: {
                    required: helpers.withMessage(
                        this.i18n.t(
                            'fieldRequiredError', 
                            {
                                fieldLabel: this.i18n.t('organizationName')
                            }
                        ), 
                        required
                    )
                }
            },
            user: {
                firstName: { 
                    required: helpers.withMessage(
                        this.i18n.t(
                            'fieldRequiredError', 
                            {
                                fieldLabel: this.i18n.t('firstName')
                            }
                        ), 
                        required
                    )
                },
                lastName: { 
                    required: helpers.withMessage(
                        this.i18n.t(
                            'fieldRequiredError', 
                            {
                                fieldLabel: this.i18n.t('lastName')
                            }
                        ), 
                        required
                    )
                },
                password: { 
                    required: helpers.withMessage(
                        this.i18n.t(
                            'fieldRequiredError', 
                            {
                                fieldLabel: this.i18n.t('password')
                            }
                        ), 
                        required
                    ),
                    minLength: helpers.withMessage(
                        this.i18n.t(
                            'minLengthError', 
                            {
                                fieldLabel: this.i18n.t('password'),
                                length: 8
                            }
                        ), 
                        minLength(8)
                    )
                },
                defaultLang: {
                    required: helpers.withMessage(
                        this.i18n.t(
                            'fieldRequiredError', 
                            {
                                fieldLabel: this.i18n.t('password')
                            }
                        ), 
                        required
                    ),
                },
                email: { 
                    required: helpers.withMessage(
                        this.i18n.t(
                            'fieldRequiredError', 
                            {
                                fieldLabel: this.i18n.t('email')
                            }
                        ), 
                        required
                    ), 
                    email: helpers.withMessage(
                        this.i18n.t('emailFormatError'), 
                        email
                    )
                },
            }
        }
    },
    methods: {
        async registerUser() {

            this.processing = true;
            this.error = null;

            const isFormCorrect = await this.v$.$validate()
            if (isFormCorrect) {

                const token = import.meta.env.VITE_API_TOKEN

                this.organization.users = [];
                this.organization.users.push(this.user)

                // Create organization
                let endpoint = import.meta.env.VITE_API_URL + '/organizations';
                let organization = await $fetch(endpoint, {
                    headers: {
                        "Content-Type": "application/json",
                        "x-api-key": token
                    },
                    method: 'POST',
                    body: this.organization
                }).then((data) => {

                    alert('user created, redirect to login')
                    console.log(data)
                    
                }).catch((error) => {
                    this.error = error.data.detail
                    switch(this.error){
                        case 'email: This email is already in use.':
                            this.$v.user.email.$error = true;
                            this.$v.user.email.$errors.push(this.error);
                        break;
                        case 'name: This value is already used.':
                            this.$v.organization.name.$error = true;
                            this.$v.organization.name.$errors.push(this.error);
                        break;
                    }
                    this.processing = false;
                })

            }

            this.processing = false;
        }
    },
};
</script>

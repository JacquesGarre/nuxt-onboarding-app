<template>
    <form role="form text-left" @submit.prevent="registerUser">
        <div class="mb-3">
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
        <div class="mb-3">
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
        <div class="form-check form-check-info text-left">
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
        <div class="text-center">
            <button type="button" class="btn bg-gradient-dark w-100 my-4 mb-2" @click="registerUser">{{ $t('signUp') }}</button>
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
            i18n: useI18n()
        }
    },
    data() {
        return {
            checkedConditions: false,
            user: {
                firstName: '',
                lastName: '',
                email: '',
                password: ''
            },
            confirmPassword: ''
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

            const isFormCorrect = await this.v$.$validate()
            if (isFormCorrect) {

                const endpoint = import.meta.env.VITE_API_URL + '/users';
                const token = import.meta.env.VITE_API_TOKEN

                let res = await $fetch(endpoint, {
                    headers: {
                        "Content-Type": "application/json",
                        "x-api-key": token
                    },
                    method: 'POST',
                    body: this.user
                });
                
                if (res.status == 201 || res.status == 200) {
                    alert('created');
                } else {
                    this.validError = res.data
                    alert('error, check console');
                    console.log(this.validError)
                }
            }

        }
    },
};
</script>

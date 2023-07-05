<template>
    <form role="form">
        <label>{{ $t('email') }}</label>
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
        <label>{{ $t('password') }}</label>
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
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="rememberMe">
            <label class="form-check-label" for="rememberMe">{{ $t('rememberMe') }}</label>
        </div>
        <div class="text-center mt-1">
            <button type="button" class="btn bg-gradient-dark w-100 my-4 mb-2" @click="signInUser" :class="{ disabled: processing }">
                <span v-if="!processing">{{ $t('signIn') }}</span>
                <span v-if="processing">{{ $t('signingIn') }}</span>
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
    import { useRegisterStore } from '~/store/register'
    import { useAuthStore } from "~/store/auth";

    export default {
        setup() {
            return {
                v$: useVuelidate(),
                i18n: useI18n(),
            }
        },
        data() {
            return {
                user: useRegisterStore().user,
                error: null,
                processing: false
            };
        },
        validations() {
            return {
                user: {
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
            async signInUser() {

                this.processing = true;
                this.error = null;
                this.success = null;
                const isFormCorrect = await this.v$.$validate()

                if (isFormCorrect) {
                    useAuthStore()
                    .login({
                        email: this.user.email,
                        password: this.user.password
                    })
                    .then((_response) => navigateTo({ path: '/admin/organization' }))
                    .catch((error) => {

                        if(error.data !== undefined){
                            this.error = error.data.message
                        } else {
                            this.error = error.name
                        }
                        this.processing = false;
                    });
                } else {
                    this.processing = false;
                }
                
            }
        }
    }

</script>


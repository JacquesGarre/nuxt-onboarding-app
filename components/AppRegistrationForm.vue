<template>
    <form role="form text-left" @submit.prevent="submitForm">
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="email-addon"
                v-model="form.name">
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon"
                v-model="form.email">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                aria-describedby="password-addon" v-model="form.password">
        </div>
        <div class="form-check form-check-info text-left">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
            <label class="form-check-label" for="flexCheckDefault">
                I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
            </label>
        </div>
        <div class="text-center">
            <button type="button" class="btn bg-gradient-dark w-100 my-4 mb-2" @click="submitForm">Sign up</button>
        </div>
    </form>
</template>
<script>

export default {
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
            },
        };
    },
    methods: {
        async submitForm() {
            let res = await this.$axios.post('/api/users/new', this.form)
            if (res.status == 201 || res.status == 200) {
                alert('created');
            } else {
                this.validError = res.data
                alert('error');
                console.log(this.validError)
            }
        }
    },
};
</script>
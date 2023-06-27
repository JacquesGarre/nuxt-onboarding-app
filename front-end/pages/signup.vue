<template>
    
  <main class="main-content  mt-0">
    <section class="min-vh-100 mb-8">
      <div class="page-header align-items-start min-vh-25 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">

        </div>
      </div>
      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-5 col-lg-5 col-md-7 mx-auto">
            <div class="card z-index-0">
              <div class="card-header text-center pt-4 pb-0">
                <h3>Register</h3>
              </div>
              <div class="card-body">
                    <AppRegistrationForm />
                    <p class="text-sm mt-3 mb-0 text-center">Already have an account? <NuxtLink to="/" class="text-info text-gradient font-weight-bold">Sign in</NuxtLink></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <AppFooter />
</template>
<script setup>
import AppRegistrationForm from '~/components/AppRegistrationForm.vue';

</script>
<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Information</h6>
                    </div>
                    <div class="card-body pt-4">
                        <AppForm id="organizationEditionForm" :values="organization" :action="updateOrganizationData"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import AppForm from '~/components/AppForm.vue';
    const { organization, updateOrganizationData } = useOrganization()
</script>

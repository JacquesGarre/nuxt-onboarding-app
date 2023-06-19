<script setup>
    import { VueFinalModal } from 'vue-final-modal'


    const props = defineProps({
        title: String,
        noBtns: Boolean
    })
    const emit = defineEmits({
        e: 'confirm',
        e: 'update:modelValue'
    })

</script>
<template>
    <VueFinalModal class="flex justify-center items-center" content-class="card" overlayTransition="vfm-fade">
        <div class="card-body">
            <h5 class="card-title">{{ title }}</h5>
            <div class="card-body">
                <slot />
            </div>
            <a class="btn bg-gradient-danger mb-0 me-2" href="javascript:;" @click="emit('update:modelValue', false)" v-if="noBtns !== true">
                {{ $t('cancelBtn') }}
            </a>
            <a class="btn bg-gradient-dark mb-0" href="javascript:;" @click="emit('confirm')" v-if="noBtns !== true">
                {{ $t('confirmBtn') }}
            </a>
        </div>
    </VueFinalModal>
</template>
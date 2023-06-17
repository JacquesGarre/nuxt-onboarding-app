<script setup>
    import { VueFinalModal } from 'vue-final-modal'

    const props = defineProps({
        title: String
    })
    const emit = defineEmits({
        e: 'confirm'
    })

</script>
<template>
    <VueFinalModal class="flex justify-center items-center" content-class="card" overlayTransition="vfm-fade">
        <div class="card-body">
            <h5 class="card-title">{{ title }}</h5>
            <div class="card-body">
                <slot />
            </div>
            <a class="btn bg-gradient-dark mb-0" href="javascript:;" @click="emit('confirm')">
                {{ $t('saveChangesBtn') }}
            </a>
        </div>
    </VueFinalModal>
</template>
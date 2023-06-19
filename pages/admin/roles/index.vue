<template>
    <AppPageHeader />

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableRoleColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableOrganizationRightsColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTablePeopleRightsColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableRolesRightsColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableProcessesRightsColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableOnboardingRightsColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableInventoryRightsColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            {{ $t('rolesTableSoftwareLicencesRightsColumn') }}</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role in useRoleStore().roles">
                                        <td class="text-center">
                                            <span class="badge bg-primary" :class="'bg-' + role.class">{{ role.name }}</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.organization">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.people">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.roles">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.processes">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.onboarding">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.inventory">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ps-0" v-for="boolean, right in role.rights.softwareLicences">
                                                <input class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="boolean">
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">{{ $t(right)}}</label>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2"
                                                data-toggle="tooltip" data-original-title="Edit role" @click="editRoleModal(role)">
                                                {{ $t('editBtn') }}
                                            </a>
                                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Remove role" @click="removeRoleModal(role)">
                                                {{ $t('deleteBtn') }}
                                            </a>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

    import { useI18n } from "vue-i18n";
    import { useModal } from 'vue-final-modal'
    import AppModal from '~/components/AppModal.vue'
    import AppForm from '~/components/AppForm.vue'
    import { useRoleStore } from '~/store/roles'

    const { roles, updateRoleData, removeRole } = useRoleStore()

    const i18n = useI18n()

    const removeRoleModal = (role) => {
        const { open, close } = useModal({
            component: AppModal,
            attrs: {
                title: i18n.t('removeRoleModalTitle'),
                onConfirm() {
                    useRoleStore().removeRole(role),
                    close()
                },
            },
            slots: {
                default: `<p>` + i18n.t('confirmRoleRemoval', {role : role.name}) + `</p>`
            },
        })
        open()
    }

    const editRoleModal = (role) => {

        const closeModal = ref(null);

        const { open, close } = useModal({
            component: AppModal,
            attrs: {
                title: i18n.t('editRoleModalTitle', {role : role.name}),
                onConfirm() {
                    close()
                },
                noBtns: true
            },
            slots: {
                default: {
                    component: AppForm,
                    attrs: {
                        id: "roleAddForm", 
                        action: updateRoleData,
                        inModal: true,
                        closeModal: closeModal,
                        values: role
                    },
                }
            },
        })

        closeModal.value = close;

        open()
    }

</script>

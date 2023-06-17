<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>{{ $t('organizationAdminsTableTitle')}} {{ organization.name }}</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ $t('organizationAdminsTableNameColumn') }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            {{ $t('organizationAdminsTableRolesColumn') }}</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ $t('organizationAdminsTableJoinedOnColumn') }}</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="admin in admins">
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img :src="admin.picture" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ admin.firstname }} {{ admin.lastname }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ admin.email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ admin.roles.join(', ') }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ admin.joinedOn }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                {{ $t('editBtn') }}
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
    const { organization, updateOrganizationData } = useOrganization()
    const admins = organization.admins
</script>
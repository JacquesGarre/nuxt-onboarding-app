export const useMenu = () => {
    const menu = ref([
        {
            type: 'separator',
            label: 'organizationSeparator',
        },
        {
            type: 'button',
            label: 'myOrganizationLabel',
            url: '/admin/organization',
            icon: 'building-user',
            iconPrefix: 'fa-solid',
            description: 'myOrganizationDescription',
            tabs: [
                {
                    label: 'infoLabel',
                    url: '/admin/organization'
                },
                {
                    label: 'adminsLabel',
                    url: '/admin/organization/admins'
                }
            ]
        },
        {
            type: 'button',
            label: 'peopleLabel',
            url: '/admin/people',
            icon: 'users',
            iconPrefix: 'fa-solid',
            description: 'peopleDescription',
            tabs: []
        },
        {
            type: 'button',
            label: 'rolesLabel',
            url: '/admin/roles',
            iconPrefix: 'fa-solid',
            icon: 'address-card',
            description: 'rolesDescription',
            tabs: []
        },
        {
            type: 'separator',
            label: 'onboardingSeparator',
        },
        {
            type: 'button',
            label: 'processesLabel',
            url: '/admin/processes',
            iconPrefix: 'fa-solid',
            icon: 'fa-exchange-alt',
            description: 'processesDescription',
            tabs: []
        },
        {
            type: 'button',
            label: 'onboardingLabel',
            url: '/admin/onboarding',
            iconPrefix: 'fa-solid',
            icon: 'fa-road',
            description: 'onboardingDescription',
            tabs: []
        },
        {
            type: 'separator',
            label: 'inventorySeparator',
        },
        {
            type: 'button',
            label: 'inventoryLabel',
            url: '/admin/inventory',
            iconPrefix: 'fa-solid',
            icon: 'fa-warehouse',
            description: 'inventoryDescription',
            tabs: []
        },
        {
            type: 'button',
            label: 'softwareLicencesLabel',
            url: '/admin/software-licences',
            iconPrefix: 'fa-solid',
            icon: 'fa-key',
            description: 'softwareLicencesDescription',
            tabs: []
        }] 
    );
    return { menu }
}

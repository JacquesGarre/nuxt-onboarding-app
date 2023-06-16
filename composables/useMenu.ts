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
            description: 'myOrganizationDescription',
            tabs: [
                {
                    label: 'infoLabel',
                    url: '/admin/organization',
                    icon: 'building-user'
                },
                {
                    label: 'adminsLabel',
                    url: '/admin/organization/admins',
                    icon: 'building-user'
                },
                {
                    label: 'settingsLabel',
                    url: '/admin/organization/settings',
                    icon: 'cog'
                }
            ]
        },
        {
            type: 'button',
            label: 'peopleLabel',
            url: '/admin/people',
            icon: 'users',
            description: 'peopleDescription',
            tabs: []
        },
        {
            type: 'button',
            label: 'rolesLabel',
            url: '/admin/roles',
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
            icon: 'fa-exchange-alt',
            description: 'processesDescription',
            tabs: []
        },
        {
            type: 'button',
            label: 'onboardingLabel',
            url: '/admin/onboarding',
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
            icon: 'fa-warehouse',
            description: 'inventoryDescription',
            tabs: []
        },
        {
            type: 'button',
            label: 'softwareLicencesLabel',
            url: '/admin/software-licences',
            icon: 'fa-key',
            description: 'softwareLicencesDescription',
            tabs: []
        }] 
    );
    return { menu }
}

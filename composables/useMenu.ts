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
            icon: 'building-user'
        },
        {
            type: 'button',
            label: 'peopleLabel',
            url: '/admin/people',
            icon: 'users'
        },
        {
            type: 'button',
            label: 'rolesLabel',
            url: '/admin/roles',
            icon: 'address-card'
        },
        {
            type: 'separator',
            label: 'onboardingSeparator',
        },
        {
            type: 'button',
            label: 'processesLabel',
            url: '/admin/processes',
            icon: 'fa-exchange-alt'
        },
        {
            type: 'button',
            label: 'onboardingLabel',
            url: '/admin/onboarding',
            icon: 'fa-road'
        },
        {
            type: 'separator',
            label: 'inventorySeparator',
        },
        {
            type: 'button',
            label: 'inventoryLabel',
            url: '/admin/inventory',
            icon: 'fa-warehouse'
        },
        {
            type: 'button',
            label: 'softwareLicencesLabel',
            url: '/admin/software-licences',
            icon: 'fa-key'
        }] 
    );
    return { menu }
}

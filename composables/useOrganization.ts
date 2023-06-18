import { useOrganizationStore } from '~/store/organization'

export const useOrganization = () => {
    const organizationStore = useOrganizationStore()
    const organization = organizationStore.organization;
    const updateOrganizationData = (data: any) => {
        organizationStore.updateOrganizationData(data)
    };
    const removeAdmin = (id: any) => {
        organizationStore.removeAdmin(id)
    };
    const addAdmin = (id: any) => {
        organizationStore.addAdmin(id)
    };
    return { 
        organization, 
        updateOrganizationData, 
        removeAdmin,
        addAdmin 
    }
}

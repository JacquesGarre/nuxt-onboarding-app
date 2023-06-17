import { useOrganizationStore } from '~/store/organization'

export const useOrganization = () => {
    const organizationStore = useOrganizationStore()
    const organization = organizationStore.organization;
    const updateOrganizationData = (data: any) => {
        organizationStore.updateOrganizationData(data)
    };
    return { organization, updateOrganizationData }
}

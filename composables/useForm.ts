import { useFormStore } from '~/store/form'

export const useForm = (formID: string) => {
    const formStore = useFormStore()
    const formSettings = formStore[formID as keyof typeof formStore];
    return { formSettings }
}

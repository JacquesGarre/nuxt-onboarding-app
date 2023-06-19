import { useFormStore } from '~/store/form'

export const useForm = (formID: string) => {

    console.log('FORM WANTED:' + formID);

    const formStore = useFormStore()

    console.log(formStore);

    const formSettings = formStore[formID as keyof typeof formStore];
    console.log(formSettings);
    return { formSettings }
}

import { createI18n } from 'vue-i18n'

import en from '../locales/en.json'
import fr from '../locales/fr.json'
import es from '../locales/es.json'
import it from '../locales/it.json'

export default defineNuxtPlugin(({ vueApp }) => {
    const i18n = createI18n({
        legacy: false,
        globalInjection: true,
        locale: 'en',
        messages: {
            en,           
            fr,
            es,
            it
        }
    })

    vueApp.use(i18n)
})
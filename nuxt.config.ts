export default defineNuxtConfig({
    devtools: { enabled: true },
    modules: ['@pinia/nuxt'],
    devServerHandlers: [],
    css: [
        'vue-final-modal/style.css',
        '@fortawesome/fontawesome-svg-core/styles.css',
        '~/assets/css/OpenSans.css',
        '~/assets/css/nucleo-icons.css',
        '~/assets/css/nucleo-svg.css',
        '~/assets/css/soft-ui-dashboard.css',
        '~/assets/css/onboarding-app.css',
    ],
    router: {
        options: {
            linkActiveClass: "active",
            linkExactActiveClass: "active"
        }
    },
    app: {
        head: {
            script: [
                {
                    src: "https://code.jquery.com/jquery-3.3.1.min.js", body: true
                },
                {
                    src: '/js/core/bootstrap.bundle.min.js', body: true
                },
                {
                    src: '/js/core/popper.min.js', body: true
                },
                {
                    src: '/js/plugins/bootstrap-notify.js', body: true
                },
                {
                    src: '/js/plugins/perfect-scrollbar.min.js', body: true
                },
                {
                    src: '/js/plugins/smooth-scrollbar.min.js', body: true
                },
                {
                    src: '/js/soft-ui-dashboard.js', body: true
                }
            ],
        }
    }
})

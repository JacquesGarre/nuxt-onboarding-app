export default defineNuxtConfig({
    devtools: { enabled: false },
    modules: [
        '@pinia/nuxt'
    ],
    css: [
        '@fortawesome/fontawesome-svg-core/styles.css',
        '~/assets/css/OpenSans.css',
        '~/assets/css/nucleo-icons.css',
        '~/assets/css/nucleo-svg.css',
        '~/assets/css/soft-ui-dashboard.min.css',
        '~/assets/css/onboarding-app.css',
    ],
    router: {
        options: {
            linkActiveClass: "",
            linkExactActiveClass: "active"
        }
    },
    app: {
        head: {
            script: [
                {
                    src: "https://code.jquery.com/jquery-3.3.1.slim.min.js",
                },
                {
                    src: '/js/core/bootstrap.bundle.min.js',
                },
                {
                    src: '/js/core/popper.min.js',
                },
                {
                    src: '/js/plugins/bootstrap-notify.js',
                },
                {
                    src: '/js/plugins/perfect-scrollbar.min.js',
                },
                {
                    src: '/js/plugins/smooth-scrollbar.min.js',
                },
                {
                    src: '/js/soft-ui-dashboard.js',
                }
            ],
        }
    }
})

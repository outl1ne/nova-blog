Nova.booting((Vue, router, store) => {
    Vue.component('index-slug-field', require('./slug-field/components/IndexField'));
    Vue.component('detail-slug-field', require('./slug-field/components/DetailField'));
    Vue.component('form-slug-field', require('./slug-field/components/SlugField'));

    Vue.component('index-published-field-posts', require('./published-field-posts/components/IndexField'));
    Vue.component('detail-published-field-posts', require('./published-field-posts/components/DetailButton'));

    Vue.component('index-markdown-field', require('./markdown-field/components/IndexMarkdownField'));

    router.addRoutes([
        {
            name: 'nova-blog',
            path: '/nova-blog',
            component: require('./blog-tool/components/Tool'),
        },
    ]);
});

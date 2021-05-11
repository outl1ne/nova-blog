import IndexSlugField from './slug-field/components/IndexField.vue';
import DetailSlugField from './slug-field/components/DetailField.vue';
import FormSlugField from './slug-field/components/SlugField.vue';
import IndexMarkdownField from './markdown-field/components/IndexMarkdownField.vue';
import NovaBlogTool from './blog-tool/components/Tool.vue';

Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-blog-slug-field', IndexSlugField);
  Vue.component('detail-nova-blog-slug-field', DetailSlugField);
  Vue.component('form-nova-blog-slug-field', FormSlugField);

  Vue.component('index-markdown-field', IndexMarkdownField);

  router.addRoutes([
    {
      name: 'nova-blog',
      path: '/nova-blog',
      component: NovaBlogTool,
    },
  ]);
});

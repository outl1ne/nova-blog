Nova.booting((Vue, router, store) => {
  Vue.component('index-slug-field', require('./components/IndexField'));
  Vue.component('detail-slug-field', require('./components/DetailField'));
  Vue.component('form-slug-field', require('./components/SlugField'));
});

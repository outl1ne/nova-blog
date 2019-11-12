Nova.booting((Vue, router, store) => {
  Vue.component('index-published-field-posts', require('./components/IndexField'));
  Vue.component('detail-published-field-posts', require('./components/DetailButton'));
});

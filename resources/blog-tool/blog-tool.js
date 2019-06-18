Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-blog',
      path: '/nova-blog',
      component: require('./components/Tool'),
    },
  ]);
});

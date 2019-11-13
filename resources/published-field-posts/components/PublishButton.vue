<template>
  <button type="button" class="ml-3 btn btn-default btn-primary" v-on:click="publish">Publish</button>
</template>

<script>
export default {
  props: ['postId'],

  methods: {
    publish() {
      Nova.request()
        .post(`/nova-vendor/nova-blog/publish/${this.postId}`)
        .then(
          response => {
            const cb = () => {
              this.$toasted.show('Draft successfully published!', { type: 'success' });
            };

            if (this.postId === response.data.id) {
              this.$router.go(null, cb);
            } else {
              this.$router.push(`/resources/posts/${response.data.id}`, cb);
            }
          },
          () => {
            this.$toasted.show('Failed to publish draft!', { type: 'error' });
          }
        );
    },
  },
};
</script>

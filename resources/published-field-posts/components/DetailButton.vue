<template>
  <panel-item :field="field">
    <template slot="value">
      <publish-indicator :published="field.value" :draft="isDraft"/>
      <publish-button v-if="!field.value" :postId="postId" ref="publishButton"/>
    </template>
  </panel-item>
</template>

<script>
import PublishButton from './PublishButton';
import PublishIndicator from './PublishIndicator';

export default {
  components: { PublishButton, PublishIndicator },
  props: ['resource', 'field'],

  data() {
    return {
      postId: this.resource.id.value,
    };
  },

  computed: {
    isDraft() {
      return !this.field.value || (this.field.value && (this.field.childDraft || this.field.draftParent));
    },
  },

  mounted() {
    if (!this.field.value) {
      const editButtonEl = document.querySelector('.content').querySelector('[dusk="edit-resource-button"]');
      editButtonEl.parentNode.append(this.$refs.publishButton.$el);
    }
  },
};
</script>

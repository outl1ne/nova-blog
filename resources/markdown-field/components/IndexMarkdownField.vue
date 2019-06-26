<template>
  <span v-html="renderedValue"></span>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    renderedValue() {
      return this.field.value;
    },
  },
};
</script>
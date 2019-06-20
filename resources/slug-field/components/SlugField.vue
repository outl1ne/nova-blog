<template>
  <default-field :field="field">
    <template slot="field">
      <input
        :id="field.name"
        type="text"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :placeholder="field.name"
        v-model="value"
      >

      <p v-if="hasError" class="my-2 text-danger">{{ firstError }}</p>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['field'],

  data() {
    return {
      value: '',
    };
  },

  methods: {
    setInitialValue() {
      return '';
    },

    fill(formData) {
      formData.append(this.field.attribute, this.value || '');
    },
  },
};
</script>
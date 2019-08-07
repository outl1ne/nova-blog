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
        @input="hasTouched = true"
      />

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
      value: this.field.value || '',
      hasTouched: false,
    };
  },

  mounted() {
    this.autoFillFromTitle();
  },

  methods: {
    setInitialValue() {
      return this.field.value;
    },

    fill(formData) {
      formData.append(this.field.attribute, this.value || '');
    },

    getSlug(text) {
      return encodeURI(
        text
          .toLowerCase()
          .replace(/\s/g, '-')
          .replace(/\*/g, '')
      );
    },

    updateTitle(container) {
      if (this.hasTouched) return;

      // Codemirror's textarea doesn't contain the entire value, so we look at the div content to get the actual value
      const value = container.querySelector('.CodeMirror-code').innerText;

      this.value = this.getSlug(value);
    },

    autoFillFromTitle() {
      // Find the correct field based on the label
      const titleContainer = document.querySelector('label[for="title"]').parentElement.parentElement;
      // Codemirror creates two textareas, so we look for the one with tabindex which is the one that receives user input
      const textarea = titleContainer.querySelector('textarea[tabindex="0"]');
      textarea.addEventListener('input', evt => this.updateTitle(titleContainer));
      textarea.addEventListener('change', evt => this.updateTitle(titleContainer));
    },
  },
};
</script>
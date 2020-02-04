<template>
  <default-field :field="field">
    <template slot="field">
      <input
        :id="field.name"
        type="text"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :placeholder="field.name"
        :value="value"
        @input="onInput"
        @blur="onBlur"
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
    onInput(evt) {
      this.value = evt.target.value;
      this.hasTouched = true;
    },

    onBlur(evt) {
      this.value = this.getSlug(this.value);
      this.$forceUpdate();
    },

    setInitialValue() {
      return this.field.value;
    },

    getSlug(text) {
      return text
        .toString()
        .toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(/[^\wüõöä\s$*_+~.()'"!\-:@]/g, '')
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, ''); // Trim - from end of text
    },

    updateTitle(titleContainer) {
      if (this.hasTouched) return;

      // Codemirror's textarea doesn't contain the entire value, so we look at the div content to get the actual value
      const codeMirrorElement = titleContainer.querySelector('.CodeMirror-code');

      let value;

      if (codeMirrorElement) {
        value = codeMirrorElement.innerText;
      } else {
        value = titleContainer.querySelector('#title').value;
      }

      this.value = this.getSlug(value);
    },

    autoFillFromTitle() {
      // Find the correct field based on the label
      const titleContainer = document.querySelector('label[for="title"]').parentElement.parentElement;

      // We support titles which are markdown textareas, or titles, so we look for both
      // Codemirror creates two textareas, so we look for the one with tabindex which is the one that receives user input
      const inputElement =
        titleContainer.querySelector('textarea[tabindex="0"]') || titleContainer.querySelector('#title');

      if (!inputElement) return;

      inputElement.addEventListener('input', evt => this.updateTitle(titleContainer));
      inputElement.addEventListener('keyup', evt => this.updateTitle(titleContainer));
    },
  },
};
</script>

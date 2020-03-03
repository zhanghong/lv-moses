<template>
  <model-form :is-edit="false" />
</template>

<script>
import ModelForm from './Form';

export default {
  name: 'EditForm',
  components: { ModelForm },
};
</script>

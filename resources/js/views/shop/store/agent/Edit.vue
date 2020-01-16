<template>
  <agent-form :is-edit="true" />
</template>

<script>
import AgentForm from './Form';

export default {
  name: 'EditForm',
  components: { AgentForm },
};
</script>


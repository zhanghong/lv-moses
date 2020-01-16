<template>
  <agent-form :is-edit="false" />
</template>

<script>
import AgentForm from './Form';

export default {
  name: 'CreateForm',
  components: { AgentForm },
};
</script>


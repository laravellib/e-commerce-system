<template>
  <div>
    <alert/>
    <navigation/>
    <nuxt/>
  </div>
</template>
<script>
  import Navigation from '@/layouts/partials/Navigation.vue';
  import Alert from '@/components/globals/Alert.vue';

  export default {
    components: {
      Navigation,
      Alert,
    },
  };
</script>
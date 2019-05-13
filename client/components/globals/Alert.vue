<template>
    <div class="alert is-info" v-if="alert" @click="clear">
        {{ alert }}
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  export default {
    computed: {
      ...mapGetters({
        alert: 'alert/message'
      })
    },

    methods: {
      ...mapActions({
        clear: 'alert/clear',
      })
    },
  }
</script>

<style>
    .alert {
        border-radius: 0;
    }
</style>

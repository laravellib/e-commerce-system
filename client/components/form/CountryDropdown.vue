<template>
    <select @change="onChanged">
        <option value="">Please select</option>
        <option
                v-for="country in countries"
                :key="country.id"
                :value="country.id"
        >
            {{ country.name }}
        </option>
    </select>
</template>

<script>
  export default {
    data() {
      return {
        countries: [],
      };
    },

    created() {
      this.fetchCountries();
    },

    methods: {
      async fetchCountries() {
        let response = await this.$axios.$get('countries');

        this.countries = response.data;
      },

      onChanged(event) {
        this.$emit('input', event.target.value);
      }
    }
  }
</script>
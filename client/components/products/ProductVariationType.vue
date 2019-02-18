<template>
    <div class="field">
        <label class="label">{{ type }}</label>
        <div class="control">
            <div class="select is-fullwidth">
                <select :value="selectedVariationId" @change="onChange($event, type)">
                    <option value="">Please choose</option>
                    <option
                            v-for="variation in variations"
                            :key="variation.id"
                            :value="variation.id"
                    >
                        {{ variation.name }}
                        <template v-if="variation.price_varies">
                            ({{ variation.price }})
                        </template>
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: {
      type: {
        type: String,
        required: true,
      },

      variations: {
        type: Array,
        default: () => [],
      },

      value: {
        type: Object,
        default: null,
      }
    },

    computed: {
      selectedVariationId() {
        if (!this.value || !this.findVariation(this.value.id)) {
          return null;
        }

        return this.value.id;
      }
    },

    methods: {
      onChange(event, type) {
        this.$emit('input', this.findVariation(event.target.value))
      },

      findVariation(id) {
        let variation = this.variations.find(v => v.id === parseInt(id));

        if (typeof variation === 'undefined') {
          return null;
        }

        return variation;
      },
    },
  };
</script>

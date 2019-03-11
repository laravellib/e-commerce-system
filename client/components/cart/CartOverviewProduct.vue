<template>
    <tr>
        <td width="120">
            <img src="http://via.placeholder.com/60x60" alt="">
        </td>
        <td>
            {{ product.product.name }} / {{ product.name }}
        </td>
        <td width="160">
            <div class="field">
                <div class="control">
                    <div class="select is-fullwidth">
                        <select>
                            <option value="0">0</option>
                            <option
                                    v-for="x in product.stock_count"
                                    :value="x"
                                    :key="x"
                                    :selected="x === product.quantity"
                            >{{ x }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </td>
        <td>
            {{ product.total }}
        </td>
        <td>
            <a href="" @click.prevent="attemptDestroy(product.id)">Remove</a>
        </td>
    </tr>
</template>

<script>
  import { mapActions } from 'vuex';

  export default {
    props: {
      product: {
        type: Object,
        required: true,
      },
    },

    methods: {
      ...mapActions({
        destroy: 'cart/destroy'
      }),

      attemptDestroy(id) {
        if (confirm('Are you sure?')) {
          this.destroy(id);
        }
      },
    }
  }
</script>
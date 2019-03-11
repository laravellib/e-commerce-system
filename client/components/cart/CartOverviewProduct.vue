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
                        <select v-model="quantity">
                            <option
                                    v-if="product.quantity === 0"
                                    value="0"
                            >0</option>
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

    data() {
      return {
        quantity: this.product.quantity,
      };
    },

    watch: {
      quantity(quantity) {
        this.update({
          productId: this.product.id,
          quantity
        })
      },
    },

    methods: {
      ...mapActions({
        destroy: 'cart/destroy',
        update: 'cart/update'
      }),

      attemptDestroy(id) {
        if (confirm('Are you sure?')) {
          this.destroy(id);
        }
      },
    }
  }
</script>
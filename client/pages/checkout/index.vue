<template>
    <section class="section">
        <div class="container is-fluid">
            <div class="columns">
                <div class="column is-8">
                    <ShippingAddress
                            :addresses="addresses"
                            v-model="form.address_id"
                    />

                    <article class="message">
                        <div class="message-body">
                            <h5 class="title is-5">Payment</h5>
                        </div>
                    </article>

                    <article v-if="shippingMethodId" class="message">
                        <div class="message-body">
                            <h5 class="title is-5">Shipping</h5>
                            <div class="select is-fullwidth">
                                <select v-model="shippingMethodId">
                                    <option
                                            v-for="shipping in shippingMethods"
                                            :key="shipping.id"
                                            :value="shipping.id"
                                    >
                                        {{ shipping.name }} ({{ shipping.price }})
                                    </option>
                                </select>
                            </div>
                        </div>
                    </article>

                    <article class="message" v-if="products.length">
                        <div class="message-body">
                            <h5 class="title is-5">Cart summary</h5>

                            <CartOverview>
                                <template slot="rows" v-if="shippingMethodId">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="has-text-weight-bold">
                                            Shipping
                                        </td>
                                        <td>
                                            {{ shipping.price }}
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="has-text-weight-bold">
                                            Total
                                        </td>
                                        <td>
                                            {{ total }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </template>
                            </CartOverview>
                        </div>
                    </article>

                    <article class="message">
                        <div class="message-body">
                            <button
                                    class="button is-info is-fullwidth is-medium"
                                    :disabled="disabled"
                                    @click="order"
                            >
                                Place order
                            </button>
                        </div>
                    </article>
                </div>
                <div class="column is-4">
                    <article class="message">
                        <div class="message-body">
                            <button
                                    class="button is-info is-fullwidth is-medium"
                                    :disabled="disabled"
                                    @click="order"
                            >
                                Place order
                            </button>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import CartOverview from "@/components/cart/CartOverview";
  import ShippingAddress from "@/components/checkout/addresses/ShippingAddress";

  export default {
    components: {
      CartOverview,
      ShippingAddress,
    },

    data() {
      return {
        submitting: false,
        addresses: [],
        shippingMethods: [],

        form: {
          address_id: null,
        },
      };
    },

    computed: {
      ...mapGetters({
        total: 'cart/total',
        products: 'cart/products',
        empty: 'cart/empty',
        shipping: 'cart/shipping',
      }),

      disabled() {
        return this.empty || this.submitting;
      },

      shippingMethodId: {
        get() {
          return this.shipping ? this.shipping.id : '';
        },
        set(shippingMethodId) {
          this.setShipping(
            this.shippingMethods.find(s => s.id === shippingMethodId)
          )
        }
      }
    },

    watch: {
      'form.address_id'(addressId) {
        this.getShippingMethods(addressId).then(() => {
          this.setShipping(this.shippingMethods[0]);
        });
      },

      shippingMethodId() {
        this.getCart();
      }
    },

    async asyncData({ app }) {
      let addresses = await app.$axios.$get('addresses');

      return {
        addresses: addresses.data,
      }
    },

    methods: {
      ...mapActions({
        setShipping: 'cart/setShipping',
        getCart: 'cart/getCart',
        flash: 'alert/flash',
      }),

      async order() {
        this.submitting = true;

        try {
          await this.$axios.$post('orders', {
            ...this.form,
            shipping_method_id: this.shippingMethodId
          });

          await this.getCart();

          this.$router.replace({ name: 'orders' });
        } catch (e) {
          this.flash(e.response.data.message);

          this.getCart();
        }

        this.submitting = false;
      },

      async getShippingMethods(addressId) {
        let response = await this.$axios.$get(`addresses/${addressId}/shipping`);

        this.shippingMethods = response.data;

        return response;
      },
    },
  }
</script>

<template>
    <div class="section">
        <div class="container is-fluid">
            <div class="columns">
                <div class="column is-half">
                    <img src="http://via.placeholder.com/620x620" alt="Product name">
                </div>
                <div class="column is-half">
                    <section class="section">
                        <h1 class="title is-4">{{ product.name }}</h1>
                        <p v-if="product.description">{{ product.description }}</p>

                        <hr>

                        <span v-if="!product.in_stock" class="tag is-rounded is-medium is-dark">
                            Out of stock
                        </span>

                        <span class="tag is-rounded is-medium">{{ product.price }}</span>
                    </section>

                    <section class="section">
                        <form action="" @submit.prevent="add">
                            <ProductVariationType
                                    v-for="(variations, type) in product.variations"
                                    :key="type"
                                    :type="type"
                                    :variations="variations"
                                    v-model="form.variation"
                            />

                            <div v-if="form.variation" class="field has-addons">
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="" id="" v-model="form.quantity">
                                            <option
                                                    v-for="x in parseInt(form.variation.stock_count)"
                                                    :key="x"
                                                    :value="x"
                                            >
                                                {{ x }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control">
                                    <button class="button is-info">Add to cart</button>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';
  import ProductVariationType from '@/components/products/ProductVariationType.vue';

  export default {
    components: {
      ProductVariationType,
    },

    data() {
      return {
        product: null,
        form: {
          variation: null,
          quantity: 1,
        }
      }
    },

    watch: {
      'form.variation': function () {
        this.form.quantity = 1;
      }
    },

    async asyncData({params, app}) {
      let response = await app.$axios.$get(`products/${params.slug}`);

      return {
        product: response.data,
      }
    },

    methods: {
      ...mapActions({
        store: 'cart/store',
      }),

      add() {
        this.store([{
          id: this.form.variation.id,
          quantity: this.form.quantity,
        }]);

        this.form = {
          variation: null,
          quantity: 1,
        }
      },
    },
  }
</script>
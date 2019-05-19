<template>
    <div class="section">
        <div class="container is-fluid">
            <div class="columns is-centered">
                <div class="column is-8">
                    <h1 class="title is-4">Your cart</h1>

                    <article v-if="products.length" class="message">
                        <CartOverview />
                    </article>

                    <p v-else>Your cart is empty</p>

                    <nuxt-link
                            :to="{ name: 'checkout' }"
                            class="button is-fullwidth is-info is-medium"
                            :disabled="empty"
                    >
                        Checkout
                    </nuxt-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import CartOverview from '@/components/cart/CartOverview.vue';

  export default {
    middleware: [
      'redirectIfGuest',
    ],

    components: {
      CartOverview,
    },

    computed: {
      ...mapGetters({
        empty: 'cart/empty',
        products: 'cart/products',
        changed: 'cart/changed',
      }),
    },
  };
</script>
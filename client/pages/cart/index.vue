<template>
    <div class="section">
        <div class="container is-fluid">
            <div class="columns is-centered">
                <div class="column is-8">
                    <h1 class="title is-4">Your cart</h1>

                    <article class="message">
                        <CartOverview />
                    </article>

                    <a href="#" class="button is-fullwidth is-info is-medium">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import CartOverview from '@/components/cart/CartOverview.vue';

  export default {
    components: {
      CartOverview,
    },
  };
</script>
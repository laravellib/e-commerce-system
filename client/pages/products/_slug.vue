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

                        <span class="tag is-rounded is-medium">{{ product.price }}</span>
                    </section>

                    <section class="section">
                        <form action="">
                            <ProductVariationType
                                v-for="(variations, type) in product.variations"
                                :key="type"
                                :type="type"
                                :variations="variations"
                            />

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="select is-fullwidth">

                                    </div>
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
  import ProductVariationType from '@/components/products/ProductVariationType.vue';

  export default {
    components: {
      ProductVariationType,
    },

    data() {
      return {
        product: null,
      }
    },

    async asyncData({params, app}) {
      let response = await app.$axios.$get(`products/${params.slug}`);

      return {
        product: response.data,
      }
    }
  }
</script>
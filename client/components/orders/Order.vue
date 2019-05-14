<template>
    <tr>
        <td>
            #{{ order.id }}
        </td>
        <td>
            {{ order.created_at }}
        </td>
        <td>
            <div v-for="variation in products" :key="variation.id">
                <nuxt-link :to="{ name: 'products-slug', params: { slug: variation.product.slug } }">
                    {{ variation.product.name }} ({{ variation.name }} - {{ variation.type }})
                </nuxt-link>
            </div>
            <template v-if="moreProducts > 0">
                and {{ moreProducts }} more
            </template>
        </td>
        <td>
            {{ order.subtotal }}
        </td>
        <td>
            <span class="tag is-medium" :class="statusClasses">
                {{ order.status }}
            </span>
        </td>
    </tr>
</template>
<script>
export default {
  props: {
    order: {
      required: true,
      type: Object,
    },

    maxProducts: {
      type: Number,
      default: 2,
    }
  },

  computed: {
    products() {
      return this.order.products.slice(0, this.maxProducts)
    },

    moreProducts() {
      return this.order.products.length - this.maxProducts;
    },

    statusClasses() {
      return {
        'is-success': this.order.status === 'complete',
        'is-info': ['processing', 'pending'].includes(this.order.status),
        'is-danger': this.order.status === 'payment_failed',
      };
    }
  }
}
</script>
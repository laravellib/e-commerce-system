<template>
    <form action="" @submit.prevent="store">
        <div class="field">
            <div ref="card"></div>
        </div>

        <div class="field">
            <div class="control">
                <button class="button is-info" :disabled="storing">Store card</button>
                <a href="#" @click.prevent="$emit('cancel')" class="button is-text">Cancel</a>
            </div>
        </div>
    </form>
</template>

<script>
  export default {
    data () {
      return {
        stripe: null,
        card: null,
        storing: false,
      };
    },

    mounted() {
      this.initStripe();
      this.initCard();
    },

    methods: {
      initStripe() {
        this.stripe = window.Stripe('pk_test_fuV0968bqdfTFk0ZiVvwzjyT');
      },

      initCard() {
        this.card = this.stripe.elements().create('card', {
          style: {
            base: {
              fontSize: '16px',
            }
          }
        });

        this.card.mount(this.$refs.card);
      },

      async store() {
        this.storing = true;

        const { token, error } = await this.stripe.createToken(this.card);

        if (error) {
            // flash error
        } else {
          let response = await this.$axios.$post('payment-methods', {
            token: token.id,
          });

          this.$emit('added', response.data);
        }

        this.storing = false;
      },
    },
  }
</script>
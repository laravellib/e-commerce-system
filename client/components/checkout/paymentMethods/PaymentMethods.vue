<template>
    <article class="message">
        <div class="message-body">
            <h5 class="title is-5">Payment method</h5>

            <template v-if="selecting">
                <PaymentMethodSelector
                        :payment-methods="paymentMethods"
                        :selected-payment-method="selectedPaymentMethod"
                        @selected="select"
                />
            </template>

            <template v-else-if="creating">
                <PaymentMethodCreator
                    @cancel="creating = false"
                    @added="create"
                />
            </template>

            <template v-else>
                <template v-if="selectedPaymentMethod">
                    <p>
                        {{ selectedPaymentMethod.card_type }} ending {{ selectedPaymentMethod.last_four }}
                    </p>

                    <br>
                </template>

                <div class="field is-grouped">
                    <div class="control" v-if="paymentMethods.length">
                        <a href="" class="button is-info" @click.prevent="selecting = true">Change payment method</a>
                    </div>
                    <div class="control">
                        <a href="" class="button is-info" @click.prevent="creating = true">Add a payment method</a>
                    </div>
                </div>
            </template>
        </div>
    </article>
</template>

<script>
  import PaymentMethodSelector from './PaymentMethodSelector.vue';
  import PaymentMethodCreator from './PaymentMethodCreator.vue';

  export default {
    components: {
      PaymentMethodSelector,
      PaymentMethodCreator,
    },

    props: {
      paymentMethods: {
        type: Array,
        required: true,
      },
    },

    data() {
      return {
        selecting: false,
        creating: false,
        localPaymentMethods: this.paymentMethods,
        selectedPaymentMethod: null,
      };
    },

    watch: {
      selectedPaymentMethod(paymentMethod) {
        this.$emit('input', paymentMethod.id);
      }
    },

    computed: {
      defaultPaymentMethod() {
        return this.localPaymentMethods.find(p => p.default === true);
      },
    },

    created() {
      if (this.paymentMethods.length) {
        this.switchPaymentMethod(this.defaultPaymentMethod);
      }
    },

    methods: {
      switchPaymentMethod(payment) {
        this.selectedPaymentMethod = payment;
      },

      select(paymentMethod) {
        this.switchPaymentMethod(paymentMethod);
        this.selecting = false;
      },

      create(payment) {
        this.localPaymentMethods.push(payment);
        this.creating = false;

        this.switchPaymentMethod(payment);
      },
    },
  }
</script>
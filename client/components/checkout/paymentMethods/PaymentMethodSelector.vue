<template>
    <table class="table is-hoverable is-fullwidth">
        <tbody>
        <tr v-for="payment in paymentMethods" :key="payment.id">
            <td>
                <p :class="{'has-text-weight-bold': payment.id === selectedPaymentMethod.id}">
                    {{ payment.card_type }} ending {{ payment.last_four }} <br>
                </p>
            </td>
            <td>
                <a href="" class="button is-info" @click.prevent="$emit('selected', payment)">Pay with this</a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
  export default {
    props: {
      paymentMethods: {
        type: Array,
        required: true,
      },

      selectedPaymentMethod: {
        type: Object,
        required: true,
      },
    },
  }
</script>
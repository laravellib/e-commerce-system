<template>
    <article class="message">
        <div class="message-body">
            <h5 class="title is-5">Ship to</h5>

            <template v-if="selecting">
                <ShippingAddressSelector
                        :addresses="addresses"
                        :selected-address="selectedAddress"
                        @input="switchAddress"
                />
            </template>
            <template v-else>
                <template v-if="selectedAddress">
                    <p>
                        {{ selectedAddress.name }} <br>
                        {{ selectedAddress.address_1 }} <br>
                        {{ selectedAddress.city }} <br>
                        {{ selectedAddress.postal_code }} <br>
                        {{ selectedAddress.country.name }}
                    </p>

                    <br>
                </template>

                <div class="field is-grouped">
                    <p class="control">
                        <a href="" class="button is-info" @click.prevent="selecting = true">Change shipping address</a>
                    </p>
                </div>
            </template>
        </div>
    </article>
</template>

<script>
  import ShippingAddressSelector from './ShippingAddressSelector.vue';

  export default {
    components: {
      ShippingAddressSelector,
    },

    props: {
      addresses: {
        type: Array,
        required: true,
      },
    },

    data() {
      return {
        selecting: false,
        localAddress: this.addresses,
        selectedAddress: null,
      };
    },

    watch: {
      selectedAddress() {
        this.selecting = false;
      }
    },

    computed: {
      defaultAddress() {
        return this.localAddress.find(address => address.default === true);
      },
    },

    created() {
      if (this.addresses.length) {
        this.switchAddress(this.defaultAddress);
      }
    },

    methods: {
      switchAddress(address) {
        this.selectedAddress = address;
      }
    },
  }
</script>
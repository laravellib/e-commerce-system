<template>
    <article class="message">
        <div class="message-body">
            <h5 class="title is-5">Ship to</h5>

            <template v-if="selecting">
                <ShippingAddressSelector
                        :addresses="addresses"
                        :selected-address="selectedAddress"
                        @selected="select"
                />
            </template>
            <template v-else-if="creating">
                <ShippingAddressCreator
                    @cancel="creating = false"
                    @created="create"
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
                    <div class="control">
                        <a href="" class="button is-info" @click.prevent="selecting = true">Change shipping address</a>
                    </div>
                    <div class="control">
                        <a href="" class="button is-info" @click.prevent="creating = true">Add an address</a>
                    </div>
                </div>
            </template>
        </div>
    </article>
</template>

<script>
  import ShippingAddressSelector from './ShippingAddressSelector.vue';
  import ShippingAddressCreator from './ShippingAddressCreator.vue';

  export default {
    components: {
      ShippingAddressSelector,
      ShippingAddressCreator,
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
        creating: false,
        localAddresses: this.addresses,
        selectedAddress: null,
      };
    },

    computed: {
      defaultAddress() {
        return this.localAddresses.find(address => address.default === true);
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
      },

      select(address) {
        this.switchAddress(address);
        this.selecting = false;
      },

      create(address) {
        this.localAddresses.push(address);
        this.creating = false;

        this.switchAddress(address);
      },
    },
  }
</script>
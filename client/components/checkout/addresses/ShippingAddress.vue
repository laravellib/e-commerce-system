<template>
    <article class="message">
        <div class="message-body">
            <h5 class="title is-5">Ship to</h5>

            <template v-if="selectedAddress">
                {{ selectedAddress.name }} <br>
                {{ selectedAddress.address_1 }} <br>
                {{ selectedAddress.city }} <br>
                {{ selectedAddress.postal_code }} <br>
                {{ selectedAddress.country.name }}
            </template>
        </div>
    </article>
</template>

<script>
  export default {
    props: {
      addresses: {
        type: Array,
        required: true,
      },
    },

    data() {
      return {
        localAddress: this.addresses,
        selectedAddress: null,
      };
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
export const state = () =>  ({
  products: [],
});

export const getters = {
  products: state => state.products,
  count: state => state.products.length,
};

export const mutations = {
  SET_PRODUCTS(state, products) {
    state.products = products;
  }
};

export const actions = {
  async getCart({ commit }) {
    let response = await this.$axios.$get('cart');

    commit('SET_PRODUCTS', response.data.products);

    return response;
  },

  async destroy({ dispatch }, productId) {
    let response = await this.$axios.$delete(`cart/${productId}`);

    dispatch('getCart');
  }
};

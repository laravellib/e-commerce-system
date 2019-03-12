export const state = () =>  ({
  products: [],
  empty: true,
  subtotal: null,
  total: null,
});

export const getters = {
  products: state => state.products,
  count: state => state.products.length,
  empty: state => state.empty,
  subtotal: state => state.subtotal,
  total: state => state.total,
};

export const mutations = {
  SET_PRODUCTS(state, products) {
    state.products = products;
  },

  SET_EMPTY(state, empty) {
    state.empty = empty;
  },

  SET_SUBTOTAL(state, subtotal) {
    state.subtotal = subtotal;
  },

  SET_TOTAL(state, btotal) {
    state.btotal = btotal;
  }
};

export const actions = {
  async getCart({ commit }) {
    let response = await this.$axios.$get('cart');

    commit('SET_PRODUCTS', response.data.products);
    commit('SET_EMPTY', response.meta.empty);
    commit('SET_SUBTOTAL', response.meta.subtotal);
    commit('SET_TOTAL', response.meta.total);

    return response;
  },

  async destroy({ dispatch }, productId) {
    await this.$axios.$delete(`cart/${productId}`);

    dispatch('getCart');
  },

  async update({ dispatch }, { productId, quantity }) {
    await this.$axios.$put(`cart/${productId}`, {
      quantity,
    });

    dispatch('getCart');
  }
};

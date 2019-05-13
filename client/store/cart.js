export const state = () => ({
  products: [],
  empty: true,
  subtotal: null,
  total: null,
  changed: false,
  shipping: null,
});

export const getters = {
  products: state => state.products,
  count: state => state.products.length,
  empty: state => state.empty,
  subtotal: state => state.subtotal,
  total: state => state.total,
  changed: state => state.changed,
  shipping: state => state.shipping,
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

  SET_TOTAL(state, total) {
    state.total = total;
  },

  SET_CHANGED(state, changed) {
    state.changed = changed;
  },

  SET_SHIPPING(state, shipping) {
    state.shipping = shipping;
  }
};

export const actions = {
  async getCart({ commit, getters }) {
    let query = {};

    if (getters.shipping) {
      query.shipping_method_id = getters.shipping.id;
    }

    let response = await this.$axios.$get('cart', {
      params: query
    });

    commit('SET_PRODUCTS', response.data.products);
    commit('SET_EMPTY', response.meta.empty);
    commit('SET_SUBTOTAL', response.meta.subtotal);
    commit('SET_TOTAL', response.meta.total);
    commit('SET_CHANGED', response.meta.changed);

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
  },

  async store({ dispatch }, products) {
    await this.$axios.$post('cart', {
      products
    });

    dispatch('getCart');
  },

  setShipping({ commit }, shipping) {
    commit('SET_SHIPPING', shipping);
  },
};

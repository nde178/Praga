// store/modules/products.js
import axios from 'axios';
const apiUrl = import.meta.env.VITE_API_URL;

export const productsModule = {
    namespaced: true,
    state: {
        products: [],
        isLoaded: false, // Флаг, чтобы избежать повторной загрузки
    },
    mutations: {
        setProducts(state, products) {
            state.products = products;
            state.isLoaded = true;
        },
    },
    actions: {
        async loadProducts({ commit, state }) {
            if (state.isLoaded) return; // Избегаем повторной загрузки
            try {
                const response = await axios.get(`${apiUrl}/api/products`);
                commit('setProducts', response.data);
                console.log("Продукты загружены:", response.data);
            } catch (error) {
                console.error("Ошибка при загрузке продуктов:", error);
            }
        },
    },
    getters: {
        allProducts: (state) => state.products,
    },
};

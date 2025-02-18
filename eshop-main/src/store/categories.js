// store/modules/categories.js
import axios from 'axios';
const apiUrl = import.meta.env.VITE_API_URL;

export const categoriesModule = {
    namespaced: true,
    state: {
        categories: [], // Хранение категорий
        isLoaded: false, // Флаг для проверки, загружены ли категории
    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
            state.isLoaded = true;
        }
    },
    actions: {
        async loadCategories({ commit, state }) {
            if (!state.isLoaded) {
                try {
                    const response = await axios.get(`${apiUrl}/api/categories`);
                    commit('setCategories', response.data); // Сохранение категорий в состоянии
                } catch (error) {
                    console.error('Ошибка при загрузке категорий:', error);
                }
            }
        }
    },
    getters: {
        allCategories: (state) => state.categories,
    }
};

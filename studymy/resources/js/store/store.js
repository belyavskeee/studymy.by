import { createStore } from 'vuex';
import axios from 'axios';
import router from '@/router/router';

const store = createStore({
  state() {
    return {
      user: null,
      token: localStorage.getItem('token') || null,
      userFullName: null,
      userPermission: null,
      userGroup: null,
      userGroupId: null,
      userId: null,
    };
  },
  mutations: {
    setUser(state, userData) {
      state.user = userData.user;
      state.userFullName = `${userData.user.lastName} ${userData.user.firstName} ${userData.user.middleName}`;
      state.userPermission = userData.user.permission;
      state.userGroup = userData.user.group;
      state.userGroupId = userData.user.group_id;
      state.userId = userData.user.id;
    },
    setToken(state, token) {
      state.token = token;
    },
    clearAuthData(state) {
      state.user = null;
      state.token = null;
      state.userFullName = null;
      state.userPermission = null;
      state.userGroup = null;
      state.userGroupId = null;
      state.userId = null;
    }
  },
  actions: {
    async login({ commit }, authData) {
      try {
        const response = await axios.post('/api/login', authData);
        const data = response.data;
        commit('setUser', data);
        commit('setToken', data.token);

        localStorage.setItem('token', data.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
        router.push('/');
      } catch (error) {
        console.error('Login failed:', error);
        throw error;
      }
    },
    async logout({ commit }) {
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('No token found');
        }

        await axios.post('/api/logout', {}, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        commit('clearAuthData');
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
        router.push('/');
      } catch (error) {
        console.error('Logout failed:', error);
        throw error;
      }
    },
    autoLogin({ commit }) {
      const token = localStorage.getItem('token');
      if (!token) {
        return;
      }
      commit('setToken', token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      axios.get('/api/user')
        .then(response => {
          commit('setUser', response.data);
        })
        .catch(error => {
          console.error('Failed to fetch user:', error);
          commit('clearAuthData');
        });
    }
  },
  getters: {
    isAuthenticated: state => !!state.token,
    userFullName: state => state.userFullName,
    userPermission: state => state.userPermission,
    userGroup: state => state.userGroup,
    userGroupId: state => state.userGroupId,
    userId: state => state.userId,
  }
});

// Вызываем действие autoLogin при создании хранилища Vuex
store.dispatch('autoLogin');

export default store;

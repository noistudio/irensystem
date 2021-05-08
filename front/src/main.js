/*!

=========================================================
* Vue Argon Design System - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-design-system
* Copyright 2019 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/argon-design-system/blob/master/LICENSE.md)

* Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import Argon from "./plugins/argon-kit";
import './registerServiceWorker'
import wysiwyg from "vue-wysiwyg";
import VS2 from 'vue-script2'
import Vuex from 'vuex'
import axios from 'axios';
import Editor from 'vue-editor-js/src/index'
import VueTimeline from "@growthbunker/vuetimeline";
import Buefy from 'buefy'
import SuiVue from 'semantic-ui-vue';
import 'semantic-ui-css/semantic.min.css';

export const eventBus = new Vue();

import '../vue.custom.config';

Vue.use(Argon);
Vue.use(VS2);
Vue.use(SuiVue);
Vue.use(wysiwyg, {});
Vue.use(Vuex);
Vue.use(Buefy)
Vue.use(Editor);

Vue.use(VueTimeline, {
	// Specify the theme to use: dark or light (dark by default).
	theme: "light",
});

console.log('api_url');

const store = new Vuex.Store({
	state: {
		api_token: localStorage.getItem('api_token') || undefined,
		isdeveloper: false,
		isauth: false,
		user: null,

	},
	getters: {
		TOKEN: state => {
			return state.api_token;
		},
		ISDEVELOPER: state => {
			return state.isdeveloper;
		},
		ISAUTH: state => {
			return state.isauth;
		},
		USER: state => {
			return state.user
		}
	},

	mutations: {

		SET_TOKEN: (state, payload) => {

			state.api_token = payload;
		},
		SET_ISDEVELOPER: (state, payload) => {
			state.isdeveloper = payload;
		},
		SET_ISAUTH: (state, payload) => {
			state.isauth = payload;
		},
		SET_USER: (state, payload) => {
			state.user = payload;
		}
	}
})


Vue.mixin({
	methods: {
		Logout() {
			var app = this;
			localStorage.removeItem('api_token');
			app.$store.commit('SET_ISAUTH', false);
			app.$store.commit('SET_USER', null);
			eventBus.$emit('user_is_login', null);
			window.location.href = "/";
		}


	}
});
const vm = new Vue({
	router,
	store,
	render: h => h(App)
}).$mount("#app");

export {vm}
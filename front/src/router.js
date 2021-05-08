import Vue from "vue";
import Router from "vue-router";
import AppHeader from "./layout/AppHeader";
import AppFooter from "./layout/AppFooter";

import Components from "./views/Components.vue";
import Landing from "./views/Landing.vue";
import Login from "./views/Login.vue";
import Home from "./views/Home.vue";
import Page from "./views/Page.vue";
import Register from "./views/Register.vue";
import Profile from "./views/Profile.vue";
import Project from "./views/Project.vue";
import Invoices from "@/views/Invoices.vue";
import Invoice from "@/views/Invoice.vue";
import OnLogin from "@/views/OnLogin.vue";
import Setup from "@/views/Setup.vue";
import Portfolio from "@/views/Portfolio.vue";
import Developer from "@/views/Developer.vue";
import axios from "axios";
import Workpage from "@/views/Workpage.vue";
import Review from "@/views/Review.vue";
import {eventBus, vm} from "@/main";
import Api from "./models/Api";

Vue.use(Router);

const guard = function (to, from, next) {
	// check for valid auth token


	Api.login(to, next);


};

export default new Router({
	linkExactActiveClass: "active",
	routes: [

		{
			path: "/",
			name: "landing",
			components: {
				header: AppHeader,
				default: Landing,
				footer: AppFooter
			}
		},


		{
			path: "/home/:rand?",
			name: "home",
			components: {
				header: AppHeader,
				default: Home,
				footer: AppFooter

			},
			beforeEnter: (to, from, next) => {
				guard(to, from, next);
			}
		},
		{
			path: "/page/:id",
			name: "page",
			components: {
				header: AppHeader,
				default: Page,
				footer: AppFooter

			}
		},
		{
			path: "/project/:id/:rand?",
			name: "project",
			components: {
				header: AppHeader,
				default: Project,
				footer: AppFooter

			},
			beforeEnter: (to, from, next) => {
				guard(to, from, next);
			}
		},
		{
			path: "/portfolio",
			name: "portfolio",
			components: {
				header: AppHeader,
				default: Portfolio,
				footer: AppFooter

			},
			beforeEnter: (to, from, next) => {
				guard(to, from, next);
			}
		},
		{
			path: "/invoices",
			name: "invoices",
			components: {
				header: AppHeader,
				default: Invoices,
				footer: AppFooter

			},
			beforeEnter: (to, from, next) => {
				guard(to, from, next);
			}
		},
		{
			path: "/invoice/:id/:rand?",
			name: "invoice",
			components: {
				header: AppHeader,
				default: Invoice,
				footer: AppFooter

			},
			beforeEnter: (to, from, next) => {
				guard(to, from, next);
			}
		},
		{
			path: "/developer/:username",
			name: "developer",
			components: {
				header: AppHeader,
				default: Developer,
				footer: AppFooter

			},

		},
		{
			path: "/developer/:username/review",
			name: "reviewpage",
			components: {
				header: AppHeader,
				default: Review,
				footer: AppFooter

			},

		},
		{
			path: "/developer/:username/:work_id",
			name: "workpage",
			components: {
				header: AppHeader,
				default: Workpage,
				footer: AppFooter

			},

		},

		{
			path: "/setup",
			name: "setup",
			components: {
				header: AppHeader,
				default: Setup,
				footer: AppFooter

			},
			beforeEnter: (to, from, next) => {
				guard(to, from, next);
			}
		},

	],
	scrollBehavior: to => {
		if (to.hash) {
			return {selector: to.hash};
		} else {
			return {x: 0, y: 0};
		}
	}
});

import axios from "axios";
import Vue from "vue";
import {eventBus, vm} from "@/main";

export default class Api {

	static loadPosts(limit, offset, success) {
		var api_token = localStorage.getItem('api_token');
		if (api_token) {

			axios.defaults.headers.common['Authorization'] = `Bearer ` + api_token;

		}
		axios.get(Vue.config.API_URL + 'blog/all/' + limit + '/' + offset)
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static deleteCommentPost(comment_id, post_id, success) {
		axios.get(Vue.config.API_URL + 'blog/delcomment/' + comment_id + '/' + post_id)
			.then(function (resp) {


				if (resp.data.type == "success") {
					success(resp.data);
				}


			})
			.catch(function () {

			});
	}

	static deleteSubCommentPost(subcomment_id, comment_id, post_id, success) {

		axios.get(Vue.config.API_URL + 'blog/delsubcomment/' + subcomment_id + '/' + comment_id + '/' + post_id)
			.then(function (resp) {


				if (resp.data.type == "success") {
					success(resp.data);
				}


			})
			.catch(function () {

			});
	}

	static loadPost(id, success) {

		(async (id, success) => {
			var api_token = await localStorage.getItem('api_token');
			if (api_token) {

				axios.defaults.headers.common['Authorization'] = `Bearer ` + api_token;

			}
			axios.get(Vue.config.API_URL + 'blog/post/' + id)
				.then(function (resp) {

					if (resp.data.type && resp.data.type == "success") {
						success(resp.data.post);
					}


				})
				.catch(function () {

				});

		})(id, success);

	}

	static loadPage(id, success) {
		axios.get(Vue.config.API_URL + 'page/' + id)
			.then(function (resp) {

				if (resp.data.type && resp.data.type == "success") {
					success(resp.data.page);
				}


			})
			.catch(function () {

			});
	}

	static loadPages(success) {

		axios.get(Vue.config.API_URL + 'pages')
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static loadAbout(success) {

		axios.get(Vue.config.API_URL + 'about')
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static loadStatsInvoices(success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'invoices/stats')
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static loadStats(success) {

		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/stats')
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static setReadAllNotify(success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'notify/readall')
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static setRemoveNotify(notify_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'notify/remove/' + notify_id)
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static setReadNotify(notify_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'notify/setread/' + notify_id)
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static loadNotifyCount(success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'notify/count')
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static sendReview(params, id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'invoices/sendreview/' + id, params)
			.then(function (resp) {

				if (resp.data.type == "success") {

					success(resp.data);
				} else {
					error(resp.data.message);
				}


			})
			.catch(function () {
				error("Произошла ошибка!");
			});
	}

	static loadNotify(params, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'notify/all', params)
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static loadUserPortfolio() {
		console.log('from api!');
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'portfolio/all')
			.then(function (resp) {


				eventBus.$emit('load_portfolio_user', resp.data);


			})
			.catch(function () {

			});
	}


	static loadUserInfo(token) {


		axios.defaults.headers.common['Authorization'] = `Bearer ` + token;
		axios.get(Vue.config.API_URL + 'user')
			.then(function (resp) {

				vm.$store.commit('SET_ISAUTH', true);
				resp.data.api_token = token;
				vm.$store.commit('SET_USER', resp.data);

				if (resp.data.isdeveloper == 1) {

					vm.$store.commit('SET_ISDEVELOPER', true);
				} else {
					vm.$store.commit('SET_ISDEVELOPER', false);
				}


				eventBus.$emit('user_is_login', resp.data);
				eventBus.$emit('update_user_info', vm.$store.getters.ISAUTH, vm.$store.getters.ISDEVELOPER);
				var url_after_redirect = localStorage.getItem('url_after_login');
				if (url_after_redirect) {
					url_after_redirect = JSON.parse(url_after_redirect);
					localStorage.removeItem('url_after_login');
					console.log('url after redirect');
					console.log(url_after_redirect);
					vm.$router.push(url_after_redirect);
				}


			})
			.catch(function () {
				eventBus.$emit('user_is_login', null);
			});
	}

	static loadBlogCategorys(callback) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'blog/categorys')
			.then(function (resp) {


				callback(resp.data);


			})
			.catch(function () {

			});
	}

	static loadCategorys(callback) {


		axios.get(Vue.config.API_URL + 'categorys/all')
			.then(function (resp) {


				callback(resp.data);


			})
			.catch(function () {

			});
	}

	static loadPortfolioCategorys() {

		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'portfolio/categorys')
			.then(function (resp) {


				eventBus.$emit('load_portfolio_categorys', resp.data);


			})
			.catch(function () {

			});
	}

	static loadInvoice(id) {


		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'invoices/get/' + id)
			.then(function (resp) {


				if (resp.data.type == "success") {
					eventBus.$emit('is_load_invoice', resp.data);
				}


			})
			.catch(function () {

			});
	}

	static loadProject(id) {


		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/get/' + id)
			.then(function (resp) {


				if (resp.data.type == "success") {

					resp.data.project.json = JSON.parse(resp.data.project.json);
					eventBus.$emit('is_load_project', resp.data);
				}


			})
			.catch(function () {

			});
	}

	static loadInvoices() {


		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'invoices/all')
			.then(function (resp) {


				eventBus.$emit('is_loaded_invoices', resp.data);


			})
			.catch(function () {

			});
	}

	static loadProjects(success) {


		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/all')
			.then(function (resp) {


				return success(resp.data);


			})
			.catch(function () {

			});
	}

	static deleteUserPortfolio(id) {

		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'portfolio/delete/' + id)
			.then(function (resp) {


			})
			.catch(function () {

			});
	}

	static removeInvoice(id, project_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/removeinvoice/' + id + "/" + project_id)
			.then(function (resp) {
				success(resp.data);


			})
			.catch(function () {

			});
	}

	static addProjectInvoice(project_id, newInvoice, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'projects/addinvoice/' + project_id, newInvoice)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data);
				} else {
					error(resp.data.message);
				}

			})
			.catch(function (resp) {
				error("Произошла ошибка!");

			});
	}

	static chooseOfferProject(project_id, offer_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'freelance/choose/' + project_id + "/" + offer_id)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.project)
				}

			})
			.catch(function (resp) {

			});
	}

	static sendPostSubComment(post_id, comment_id, comment, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'blog/comments/sendsub/' + post_id + "/" + comment_id, {"comment": comment})
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.comments)
				}

			})
			.catch(function (resp) {

			});
	}

	static sendSubComment(project_id, comment_id, comment, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'comments/sendsub/' + project_id + "/" + comment_id, {"comment": comment})
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.comments)
				}

			})
			.catch(function (resp) {

			});
	}

	static sendOfferComment(project_id, offer_id, comment, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'freelance/sendcomment/' + project_id + "/" + offer_id, {"comment": comment})
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.comments)
				}

			})
			.catch(function (resp) {

			});
	}

	static sendOffer(newOffer, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'freelance/send/' + newOffer.project_id, newOffer)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.offer)
				} else {
					error(resp.data.message);
				}

			})
			.catch(function (resp) {

				error("Произошла ошибка!");
			});
	}


	static addPostComment(newComment, post_id, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'blog/commentadd/' + post_id, newComment)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.comment)
				} else {
					error(resp.data.message);
				}

			})
			.catch(function (resp) {

				error("Произошла ошибка!");
			});
	}

	static addComment(newComment, project_id, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'comments/add/' + project_id, newComment)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.comment)
				} else {
					error(resp.data.message);
				}

			})
			.catch(function (resp) {

				error("Произошла ошибка!");
			});
	}

	static deleteSpectator(project_id, user_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'users/delete/' + project_id + "/" + user_id)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.users);


				} else {

					//   error(resp.data.message)
				}

			})
			.catch(function (resp) {
				// error("Произошла ошибка!")

			});
	}

	static addUser(project_id, username, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'users/add/' + project_id, {"username": username})
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success(resp.data.users);


				} else {

					error(resp.data.message)
				}

			})
			.catch(function (resp) {
				error("Произошла ошибка!")

			});
	}

	static setAccount(account, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'setaccount', {"account": account})
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success();


				} else {

					error(resp.data.message)
				}

			})
			.catch(function (resp) {
				error("Произошла ошибка!")

			});
	}

	static setNewStatus(status_id, project_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/newstatus/' + project_id + "/" + status_id)
			.then(function (resp) {


				success();


			})
			.catch(function () {

			});
	}

	static approveInvoice(id, project_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/approveinvoice/' + id + "/" + project_id)
			.then(function (resp) {


				success(resp.data);


			})
			.catch(function () {

			});
	}

	static completeInvoice(id, project_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'projects/completeinvoice/' + id + "/" + project_id)
			.then(function (resp) {

				success(resp.data);


			})
			.catch(function () {

			});
	}

	static login(to, next) {
		console.log('to route');
		console.log(to);
		var token = localStorage.getItem('api_token');

		axios.defaults.headers.common['Authorization'] = `Bearer ` + token;
		axios.get(Vue.config.API_URL + 'user')
			.then(function (resp) {

				next();

			})
			.catch(function () {
				localStorage.setItem('url_after_login', JSON.stringify(to));
				vm.$router.push({"name": "landing"});


				// this.$router.push({"name": "project", "params": {"id": button.params.id, "rand": Math.random()}});
				// vm.$forceUpdate();
				// window.location.href = "/";
			});
	}

	static addWork(newWork, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'portfolio/add', newWork)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {
					success();

				} else {
					error(resp.data.message);

				}

			})
			.catch(function (resp) {

				error("Произошла ошибка!");
			});
	}

	static invoiceSetPay(id, callback) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'invoices/setpay/' + id)
			.then(function (resp) {

				if (resp.data.type == "success") {
					callback();
				}


			})
			.catch(function () {

			});
	}

	static invoiceFinishPay(id, callback) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'invoices/finishpay/' + id)
			.then(function (resp) {

				if (resp.data.type == "success") {
					callback()
				}


			})
			.catch(function () {

			});
	}

	static addTask(editProject, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'projects/addtask/' + editProject.last_id, editProject)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {

					success(resp.data.tasks);
				} else {

					error(resp.data.message);
				}

			})
			.catch(function (resp) {
				error("Произошла ошибка");

			});
	}

	static editProject(editProject, success, error) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'projects/edit_project/' + editProject.last_id, editProject)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {

					success({iserror: false});
				} else {

					error(resp.data.message);
				}

			})
			.catch(function (resp) {
				error("Произошла ошибка");

			});
	}

	static changeEnablePost(post_id, success) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'blog/enable/' + post_id)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {

					success(resp.data.post);
				}

			})
			.catch(function (resp) {


			});
	}

	static createPost(newPost, callback) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'blog/addpost', newPost)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {

					callback({iserror: false, post_id: resp.data.post_id});
				} else {

					callback({iserror: true, error_message: resp.data.message});
				}

			})
			.catch(function (resp) {
				callback({iserror: true, error_message: "Произошла ошибка"});

			});
	}

	static createProject(newProject, callback) {
		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.post(Vue.config.API_URL + 'projects/add', newProject)
			.then(function (resp) {
				//alert('Успешно!');
				if (resp.data.type == "success") {

					callback({iserror: false, project_id: resp.data.project_id});
				} else {

					callback({iserror: true, error_message: resp.data.message});
				}

			})
			.catch(function (resp) {
				callback({iserror: true, error_message: "Произошла ошибка"});

			});
	}

	static loadDeveloperReviews(username, success) {


		axios.get(Vue.config.API_URL + 'developer/reviews/' + username)
			.then(function (resp) {

				if (resp.data.type == "success") {
					var developer_data = resp.data.developer;


					success(developer_data);
				}


			})
			.catch(function () {

			});
	}

	static loadDeveloperWork(username, work_id, success) {


		axios.get(Vue.config.API_URL + 'developer/work/' + username + "/" + work_id)
			.then(function (resp) {

				if (resp.data.type == "success") {
					var developer_data = resp.data.developer;
					var work = resp.data.work;


					success(developer_data, work);
				}


			})
			.catch(function () {

			});
	}

	static loadDeveloper(username) {


		axios.get(Vue.config.API_URL + 'developer/' + username)
			.then(function (resp) {

				if (resp.data.type == "success") {
					var developer_data = resp.data.developer;


					eventBus.$emit('load_developer_user', developer_data);
				}


			})
			.catch(function () {

			});
	}

	static loadUserPortfolio() {

		axios.defaults.headers.common['Authorization'] = `Bearer ` + vm.$store.getters.TOKEN;
		axios.get(Vue.config.API_URL + 'portfolio/all')
			.then(function (resp) {


				eventBus.$emit('load_portfolio_user', resp.data);


			})
			.catch(function () {

			});
	}


}
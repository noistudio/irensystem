<template>
  <header class="header-global">
    <modal modal-classes="modal-lg" :show.sync="modals.add_project" :key="islogin">
      <template slot="header">
        <h5 class="modal-title" id="exampleModalLabel">{{ modal_add_title }}</h5>
      </template>
      <div>
        <div class="col-md-12" v-if="islogin==false">
          <p>Для размещение проекта вам нужно авторизоваться</p>
          <vue-telegram-login v-if="istgbot" id="telegram_login"
                              mode="callback"
                              requestAccess="write"
                              :telegram-login="telegram_bot"

                              @callback="telegramLoginCallback"/>
        </div>

        <div class="alert alert-danger" v-if="iserror==true && islogin==true">
          <p>{{ error_message }}</p>
        </div>
        <div class="form-group" v-if="islogin==true && is_show_category==true">
          <label for="exampleCategory">Тип </label>
          <select id="exampleCategory" v-if="loaded_categorys==true" v-model="form.category" required
                  class="form-control">
            <option v-if="categorys[0].name" v-for="category in categorys" :value="category.last_id"
                    :key="category.last_id">{{
                category.name
              }}
            </option>
            <option v-if="categorys[0].title" v-for="category in categorys" :value="category.last_id"
                    :key="category.last_id">{{
                category.title
              }}
            </option>

          </select>


        </div>
        <div class="form-group" v-if="modal_params.iswork">
          <label for="exampleDateStart">Дата начала </label>
          <input type="date" id="exampleDateStart" class="form-control" v-model="modal_params.newWork.date_start"
                 required/>
        </div>
        <div class="form-group" v-if="modal_params.iswork">
          <label for="exampleDateEnd">Дата завершения </label>
          <input type="date" id="exampleDateEnd" class="form-control" v-model="modal_params.newWork.date_end"
                 required/>
        </div>
        <div class="form-group" v-if="modal_params.iswork">
          <label for="exampleCategory">Категория </label>
          <select id="exampleCategory" v-if="loaded_categorys==true" v-model="modal_params.newWork.category"
                  required
                  class="form-control">
            <option v-for="category in categorys" :value="category.last_id" :key="category.last_id">
              {{ category.name }}
            </option>

          </select>


        </div>
        <div class="form-group row"
             v-if="islogin==true && modal_params.type_user && modal_params.type_user=='developer' && is_add_task">
          <label for="exampleUser">Исполнитель задачи</label>
          <select id="exampleUser" required v-model="new_task_developer"
                  class="form-control">
            <option value="me">Я</option>

            <option
                v-if="user_project.last_id!=user.last_id && user_project.isapprove==1 && user_project.role=='spectator'"
                v-for="user_project in modal_params.users"
                :value="user_project.last_id"
                :key="user_project.last_id">@{{
                user_project.name
              }}
            </option>

          </select>


        </div>

        <Editor v-if="islogin==true" ref="editor" :config="config"/>


      </div>
      <template slot="footer">
        <base-button type="secondary" @click="modals.add_project = false">Закрыть</base-button>

        <base-button v-if="islogin" @click="invokeSave" type="primary">{{ modal_btn_add_project }}</base-button>
      </template>
    </modal>
    <base-nav class="navbar-main" transparent type="" effect="light" expand>
      <div slot="brand" class="navbar-brand mr-lg-5">
        <router-link class="navbar-brand" to="/">
          Artemdev
        </router-link>
        <base-button class="btn btn-warning" type="warning" @click="addProject(true)">
          Разместить проект
        </base-button>
      </div>


      <div class="row" slot="content-header" slot-scope="{closeMenu}">
        <div class="col-6 collapse-brand">
          <router-link slot="brand" class="btn btn-warning navbar-brand mr-lg-5" to="/">
            Artemdev
          </router-link>


        </div>
        <div class="col-6 collapse-close">
          <close-button @click="closeMenu"></close-button>
        </div>
      </div>

      <ul class="navbar-nav navbar-nav-hover align-items-lg-center" style="">


        <li class="nav-item">
          <router-link to="/" class="nav-link">О сайте</router-link>

        </li>
        <base-dropdown class="nav-item" menu-classes="dropdown-menu-xl" v-if="is_load_pages">\

          <a slot="title" href="#" class="nav-link" data-toggle="dropdown" role="button">
            <i class="ni ni-ui-04 d-lg-none"></i>
            <span class="nav-link-inner--text">Страницы</span>
          </a>
          <div class="dropdown-menu-inner">
            <router-link v-for="(page,page_key) in pages" :value="page_key"
                         :to="{name: 'page', params: { id: page.last_id }}"
                         class="media d-flex align-items-center"
                         tag="a">

              <div class="icon icon-shape bg-gradient-primary rounded-circle text-white">
                <i v-bind:class="page.icon"></i>
              </div>
              <div class="media-body ml-3">
                <h6 class="heading text-primary mb-md-1">{{ page.title }}</h6>
                <p class="description d-none d-md-inline-block mb-0">{{ page.description }}.</p>
              </div>

            </router-link>

          </div>
        </base-dropdown>
        <li class="nav-item" v-if="islogin==true">
          <router-link to="/home" class="nav-link">Проекты</router-link>

        </li>
        <li class="nav-item" v-if="islogin==true">
          <router-link to="/invoices" class="nav-link">Счета</router-link>

        </li>


      </ul>
      <ul class="navbar-nav align-items-lg-center ml-lg-auto">

        <li class="nav-item" v-if="islogin==false">
          <base-button class="btn btn-success" type="success" @click="addProject(false)">
            Войти\Зарегистрироваться
          </base-button>
        </li>
        <li class="nav-item" v-if="islogin==true">
          <base-notify-dropdown class="nav-item" menu-classes="dropdown-menu-xl">
            <a slot="title" href="#" class="nav-link" @click="loadNotify" data-toggle="dropdown" role="button">
              <i class="ni ni-ui-04 d-lg-none"></i>
              <span class="nav-link-inner--text"><i class="fa fa-bell"></i>  <badge v-if="notify_count>0"
                                                                                    type="danger">{{
                  notify_count
                }}</badge></span>
            </a>
            <div class="dropdown-menu-inner dropdown-menu-xl2">
              <sui-segment v-if="is_notify_load==false">
                <sui-dimmer active>
                  <sui-loader size="tiny"></sui-loader>
                </sui-dimmer>

              </sui-segment>
              <div v-if="is_notify_load">
                <sui-form-field>
                  <sui-checkbox toggle @change="changeNotifyType" v-model="notify.show_only_unread"
                                label="Показывать только не прочитанные"/>
                </sui-form-field>
                <p><a @click="markerAllNotifyRead">Пометить все прочитанным</a></p>
                <sui-tab class="notify_bar" :menu="{ vertical: true, fluid:true, tabular: 'left' }">
                  <sui-tab-pane title="Все">
                    <sui-feed>
                      <sui-feed-event
                          v-if="(notify.show_only_unread==true && notify_object.isread==0) || (notify.show_only_unread==false)"
                          v-for="(notify_object,notify_index) in notifys" :key="notify_object.last_id">

                        <sui-feed-content>
                          <sui-feed-summary
                              :date="notify_object.created_at"
                              :user="notify_object.who.name"
                              :content="' '+notify_object.json.title"
                          />
                          <sui-feed-extra>
                            <div v-for="(button,button_index) in notify_object.json.buttons" :key="button_index">
                              <a @click="clickNotify(button,notify_object,notify_index)">{{ button.title }}</a>&nbsp;&nbsp;
                              &nbsp;
                            </div>

                          </sui-feed-extra>
                          <sui-feed-meta>
                            <sui-feed-like>

                            </sui-feed-like>
                          </sui-feed-meta>
                        </sui-feed-content>
                      </sui-feed-event>


                    </sui-feed>
                  </sui-tab-pane>

                  <sui-tab-pane icon="folder open" :title="'('+notify_counts.projects+')'">
                    <h3 is="sui-header" dividing>Проекты</h3>
                    <sui-feed>
                      <sui-feed-event v-if="notify_object.json.type=='projects'" v-for="notify_object in notifys"
                                      :key="notify_object.last_id">
                        <sui-feed-label>

                        </sui-feed-label>
                        <sui-feed-content>
                          <sui-feed-summary
                              :date="notify_object.created_at"
                              :user="notify_object.who.name"
                              :content="' '+notify_object.json.title"
                          />
                          <sui-feed-extra>
                            <div v-for="(button,button_index) in notify_object.json.buttons" :key="button_index">
                              <a @click="clickNotify(button,notify_object)">{{ button.title }}</a>&nbsp;&nbsp;
                              &nbsp;
                            </div>

                          </sui-feed-extra>
                          <sui-feed-meta>
                            <sui-feed-like>

                            </sui-feed-like>
                          </sui-feed-meta>
                        </sui-feed-content>
                      </sui-feed-event>


                    </sui-feed>

                  </sui-tab-pane>
                  <sui-tab-pane icon="shopping cart" :title="'('+notify_counts.freelance+')'">
                    <h3 is="sui-header" dividing>Фриланс</h3>
                    <sui-feed>
                      <sui-feed-event v-if="notify_object.json.type=='freelance'" v-for="notify_object in notifys"
                                      :key="notify_object.last_id">
                        <sui-feed-label>

                        </sui-feed-label>
                        <sui-feed-content>
                          <sui-feed-summary
                              :date="notify_object.created_at"
                              :user="notify_object.who.name"
                              :content="' '+notify_object.json.title"
                          />
                          <sui-feed-extra>
                            <div v-for="(button,button_index) in notify_object.json.buttons" :key="button_index">
                              <a @click="clickNotify(button,notify_object)">{{ button.title }}</a>&nbsp;&nbsp;
                              &nbsp;
                            </div>

                          </sui-feed-extra>
                          <sui-feed-meta>
                            <sui-feed-like>

                            </sui-feed-like>
                          </sui-feed-meta>
                        </sui-feed-content>
                      </sui-feed-event>


                    </sui-feed>

                  </sui-tab-pane>
                  <sui-tab-pane icon="money bill" :title="'('+notify_counts.invoices+')'">
                    <h3 is="sui-header" dividing>Счета</h3>
                    <sui-feed>
                      <sui-feed-event v-if="notify_object.json.type=='invoices'" v-for="notify_object in notifys"
                                      :key="notify_object.last_id">
                        <sui-feed-label>

                        </sui-feed-label>
                        <sui-feed-content>
                          <sui-feed-summary
                              :date="notify_object.created_at"
                              :user="notify_object.who.name"
                              :content="' '+notify_object.json.title"
                          />
                          <sui-feed-extra>
                            <div v-for="(button,button_index) in notify_object.json.buttons" :key="button_index">
                              <a @click="clickNotify(button,notify_object)">{{ button.title }}</a>&nbsp;&nbsp;
                              &nbsp;
                            </div>

                          </sui-feed-extra>
                          <sui-feed-meta>
                            <sui-feed-like>

                            </sui-feed-like>
                          </sui-feed-meta>
                        </sui-feed-content>
                      </sui-feed-event>

                    </sui-feed>
                  </sui-tab-pane>
                  <sui-tab-pane icon="comment" :title="'('+notify_counts.comments+')'">
                    <h3 is="sui-header" dividing>Общение</h3>
                    <sui-feed>
                      <sui-feed-event v-if="notify_object.json.type=='comments'" v-for="notify_object in notifys"
                                      :key="notify_object.last_id">
                        <sui-feed-label>

                        </sui-feed-label>
                        <sui-feed-content>
                          <sui-feed-summary
                              :date="notify_object.created_at"
                              :user="notify_object.who.name"
                              :content="' '+notify_object.json.title"
                          />
                          <sui-feed-extra>
                            <div v-for="(button,button_index) in notify_object.json.buttons" :key="button_index">
                              <a @click="clickNotify(button,notify_object)">{{ button.title }}</a>&nbsp;&nbsp;
                              &nbsp;
                            </div>

                          </sui-feed-extra>
                          <sui-feed-meta>
                            <sui-feed-like>

                            </sui-feed-like>
                          </sui-feed-meta>
                        </sui-feed-content>
                      </sui-feed-event>


                    </sui-feed>
                  </sui-tab-pane>
                </sui-tab>
              </div>
            </div>
          </base-notify-dropdown>
          <base-dropdown tag="li" class="nav-item">
            <a slot="title" href="#" class="nav-link" data-toggle="dropdown" role="button">
              <i class="ni ni-collection d-lg-none"></i>
              <span class="nav-link-inner--text">{{ user.name }}</span>
            </a>
            <router-link to="/setup" class="dropdown-item">Настройки</router-link>

            <router-link v-if="user.isdeveloper==1" to="/portfolio" class="dropdown-item">Портфолио</router-link>
            <router-link v-if="user.isdeveloper==1" :to="{name: 'developer', params: { username: user.username }}"
                         class="dropdown-item">Ваш профиль
            </router-link>
          </base-dropdown>

        </li>
        <li class="nav-item" v-if="islogin==true">
          <button @click="this.Logout" class="btn btn-danger"><i class="fa fa-user-secret"></i> Выход</button>

        </li>

      </ul>
    </base-nav>
  </header>
</template>
<script>
import BaseNav from "@/components/BaseNav";
import BaseDropdown from "@/components/BaseDropdown";
import BaseNotifyDropdown from "@/components/BaseNotifyDropdown";
import CloseButton from "@/components/CloseButton";
import {vueTelegramLogin} from 'vue-telegram-login'
import axios from 'axios';
import {eventBus} from '../main.js';
import Vue from "vue";
import Api from "../models/Api";
import {vm} from "../main.js";
import Modal from "@/components/Modal";

require('dotenv').config()

export default {

  components: {
    Modal,
    BaseNav,
    CloseButton,
    BaseDropdown,
    BaseNotifyDropdown,
    vueTelegramLogin
  },

  data: function () {

    return {
      "telegram_bot": null,
      user: {},
      is_load_pages: false,
      pages: [],

      is_notify_load: false,
      new_task_developer: null,
      "loaded_categorys": false,
      "categorys": null,
      "notifys": null,
      "notify": {
        "show_only_unread": true,
      },
      'modal_params': {},
      "modals": {
        "add_project": null,
      },
      "notify_counts": {
        "projects": 0,
        "freelance": 0,
        "comments": 0,
        "invoices": 0,
      },
      "modal_add_title": "Размещение проекта",
      "is_show_category": true,
      "is_add_task": false,
      "iserror": false,
      "error_message": null,
      "modal_btn_add_project": "Отправить на оценку",
      "form": {
        'category': null,
        'json': {},
      },
      "config": {},
      notify_count: 0,
      islogin: false,
      'istgbot': false,
      'timer': '',
      show_modal_after_login: true,
    }
  },
  mounted: function () {
    var app = this;

    eventBus.$on("call_modal_add_project", data => {
      app.modal_params = data;
      app.loaded_categorys = false;
      if (app.modal_params.type && (app.modal_params.type == "post_new" || app.modal_params.type == "post_edit")) {
        Api.loadBlogCategorys(function (data) {
          app.categorys = data;
          app.loaded_categorys = true;
        });
      } else if (app.modal_params.type && (app.modal_params.type == "addwork" || app.modal_params.type == "editwork")) {
        Api.loadPortfolioCategorysModal(function (data) {
              app.is_show_category = false;
              app.categorys = data;
              app.loaded_categorys = true;
            }
        );
      } else {
        Api.loadCategorys(function (data) {
          app.categorys = data;
          app.loaded_categorys = true;
        });
      }

      if (app.modal_params.type && app.modal_params.type == "edit") {
        app.editProject();
      } else if (app.modal_params.type && app.modal_params.type == "addwork") {
        app.addWork();
      } else if (app.modal_params.type && app.modal_params.type == "editwork") {
        app.editWork();
      } else if (app.modal_params.type && app.modal_params.type == "addtask") {
        app.addTask();
      } else if (app.modal_params.type && app.modal_params.type == "post_new") {
        app.addPost();
      } else if (app.modal_params.type && app.modal_params.type == "post_edit") {
        app.editPost();
      } else {
        app.addProject();
      }

    })
    app.telegram_bot = Vue.config.TG_BOT;
    app.istgbot = true;
    Api.loadPages(function (data) {

      app.pages = data;
      if (app.pages.length > 0) {
        app.is_load_pages = true;
      }

    });
    Api.loadCategorys(function (data) {
      app.categorys = data;
      app.loaded_categorys = true;
    });
    if (this.$store.getters.ISAUTH) {
      var tmpUser = this.$store.getters.USER;
      this.islogin = true;
      this.user = tmpUser;
      app.updateNotifyCount();
      app.timer = setInterval(app.updateNotifyCount, 2000);
      app.updateEditorJs(this.user.api_token);
    } else {

      eventBus.$on('user_is_login', user => {
        this.islogin = false;
        if (typeof user === 'object') {
          if (user.api_token) {
            app.user = user;
            app.islogin = true;
            app.updateNotifyCount();
            app.timer = setInterval(app.updateNotifyCount, 5000);
            app.updateEditorJs(user.api_token);
          }
        }


      });

    }
    console.log('sample user');
    console.log(this.$store);
    // let tg_widget = document.createElement('script');
    // tg_widget.setAttribute("src", "https://telegram.org/js/telegram-widget.js?14");
    // tg_widget.setAttribute("data-telegram-login", "Noibizcombot");
    // tg_widget.setAttribute("data-size", "small");
    // tg_widget.setAttribute("data-request-access", "write");
    // //tg_widget.setAttribute("data-onauth", "this.telegramLoginCallback(user)");
    // tg_widget.setAttribute("data-auth-url", "http://1aaa54bb86e2.ngrok.io/#/onlogin");
    // tg_widget.setAttribute("async", "");
    //
    // this.$refs.telegram_login.appendChild(tg_widget);
  },
  methods: {
    testLog() {
      console.log('console log appheader method');
    },
    changeNotifyType() {
      this.loadNotify();
    },
    updateEditorJs(api_token) {
      this.config = {

        tools: {
          header: require('@editorjs/header'),
          list: {
            class: require('@editorjs/list'),
            inlineToolbar: true,
          },
          "code": require('@editorjs/code'),
          inlineCode: {
            class: require('@editorjs/inline-code'),
            shortcut: 'CMD+SHIFT+M',
          },

          embed: require('@editorjs/embed'),
          linkTool: {
            class: require('@editorjs/link'),
            config: {
              endpoint: Vue.config.API_URL + 'editor/fetchUrl', // Your backend endpoint for url data fetching
            }
          },
          Marker: {
            class: require('@editorjs/marker'),
            shortcut: 'CMD+SHIFT+M',
          },
          table: {
            class: require('@editorjs/table'),
          },
          raw: require('@editorjs/raw'),
          delimiter: require('@editorjs/delimiter'),
          quote: {
            class: require('@editorjs/quote'),
            inlineToolbar: true,
            shortcut: 'CMD+SHIFT+O',
            config: {
              quotePlaceholder: 'Enter a quote',
              captionPlaceholder: 'Quote\'s author',
            },
          },
          attaches: {
            class: require('@editorjs/attaches'),
            config: {
              endpoint: Vue.config.API_URL + 'editor/uploadFile?api_token=' + api_token,
            }
          },
          image: {
            class: require('@editorjs/image'),
            config: {
              additionalRequestHeaders: {
                "Authorization": 'Bearer ' + api_token
              },
              endpoints: {
                byFile: Vue.config.API_URL + 'editor/uploadImage', // Your backend file uploader endpoint

              }

            }
          },
          warning: require('@editorjs/warning'),
          paragraph: {
            class: require('@editorjs/paragraph'),
            inlineToolbar: true,
          },
          checklist: {
            class: require('@editorjs/checklist'),
            inlineToolbar: true,
          },
        },
        "data": {
          "time": 1591362820044,
          "blocks": [
            {
              "type": "header",
              "data": {
                "text": "Название вашего замечательного проекта!",
                "level": 2
              }
            },
            {
              "type": "paragraph",
              "data": {
                "text": "Здесь могут быть описано базовое описание вашего проекта. а также разных требований например:",
              }
            },
            {
              "type": "list",
              "data": {
                "style": "unordered",
                "items": [
                  "Знать Laravel",
                  "Уметь работать с Vue.js",
                  "Уметь работать с REST API"
                ]
              }
            }
          ]
        }
      };
    },
    updateNotifyCount() {
      if (!this.$store.getters.ISAUTH) {
        clearInterval(this.timer);
      }
      var app = this;

      Api.loadNotifyCount(function (notifys) {


        app.notify_count = notifys.count;


      });
    },
    editWork() {
      this.show_modal_after_login = true;
      this.is_add_task = false;
      this.is_show_category = true;
      this.form.category = null;
      this.modals.add_project = true;
      this.modal_add_title = this.modal_params.modal_title;
      this.modal_btn_add_project = "Сохранить изменения";
      this.$refs.editor._data.state.editor.render(this.modal_params.json);
    },
    addPost() {
      this.show_modal_after_login = true
      this.is_add_task = false;
      this.is_show_category = true;
      this.form.category = null;
      this.modals.add_project = true;
      this.modal_add_title = this.modal_params.modal_title;
      this.modal_btn_add_project = "Добавить пост";
      this.$refs.editor._data.state.editor.render(this.modal_params.json);
    },
    editPost() {
      this.show_modal_after_login = true;
      this.is_add_task = false;
      this.is_show_category = true;
      this.form.category = null;
      this.modals.add_project = true;
      this.modal_add_title = this.modal_params.modal_title;
      this.modal_btn_add_project = "Редактировать пост";
      this.form.category = this.modal_params.category;
      this.$refs.editor._data.state.editor.render(this.modal_params.json);
    },
    addWork() {
      this.show_modal_after_login = true;
      this.is_add_task = false;
      this.is_show_category = true;
      this.form.category = null;
      this.modals.add_project = true;
      this.modal_add_title = this.modal_params.modal_title;
      this.modal_btn_add_project = "Добавить работу";
      this.$refs.editor._data.state.editor.render(this.modal_params.json);
    },
    addTask() {
      this.show_modal_after_login = true;
      this.is_add_task = true;
      this.is_show_category = false;
      this.form.category = null;
      this.modals.add_project = true;
      this.modal_add_title = this.modal_params.modal_title;
      this.modal_btn_add_project = "Добавить задачу";
      this.$refs.editor._data.state.editor.render(this.modal_params.json);
    },
    editProject() {
      this.show_modal_after_login = true;
      this.is_add_task = false;
      this.is_show_category = false;
      this.form.category = null;
      this.modals.add_project = true;
      this.modal_add_title = this.modal_params.modal_title;
      this.modal_btn_add_project = "Сохранить изменения!";
      this.$refs.editor._data.state.editor.render(this.modal_params.json);
    },
    addProject(show_modal_after_login = true) {
      console.log('status login');
      console.log(this.islogin);
      if (this.islogin) {
        this.modal_add_title = "Размещение проекта";
      } else {
        this.modal_add_title = "Авторизация в системе";
      }
      this.show_modal_after_login = show_modal_after_login;
      this.is_add_task = false;
      this.modal_params = {};
      this.is_show_category = true;
      this.form.category = null;
      this.modals.add_project = true;
      this.$refs.editor._data.state.editor.render(this.config.data);
      this.modal_btn_add_project = "Отправить на оценку";

    },
    loadNotify() {
      var app = this;
      app.is_notify_load = false;
      app.notify_counts.comments = 0;
      app.notify_counts.freelance = 0;
      app.notify_counts.projects = 0;
      app.notify_counts.invoices = 0;
      Api.loadNotify(app.notify, function (notifys) {

        var projects_count = 0;
        var freelance_count = 0;
        var invoices_count = 0;
        var comments_count = 0;
        if (notifys.length) {
          notifys.forEach(function callback(currentValue, index, array) {
            if (currentValue.isread == 0 && currentValue.json.type == "projects") {
              projects_count++;
            }
            if (currentValue.isread == 0 && currentValue.json.type == "freelance") {
              freelance_count++;
            }
            if (currentValue.isread == 0 && currentValue.json.type == "comments") {
              comments_count++;
            }
            if (currentValue.isread == 0 && currentValue.json.type == "invoices") {
              invoices_count++;
            }
          })
          app.notify_counts.comments = comments_count;
          app.notify_counts.freelance = freelance_count;
          app.notify_counts.projects = projects_count;
          app.notify_counts.invoices = invoices_count;
        }


        app.notifys = notifys;
        app.is_notify_load = true;

      });
    },
    markerAllNotifyRead() {
      var app = this;
      Api.setReadAllNotify(function () {
        app.notify_count = 0;
        app.notifys = [];
        app.is_notify_load = false;
        app.notify_counts.comments = 0;
        app.notify_counts.freelance = 0;
        app.notify_counts.projects = 0;
        app.notify_counts.invoices = 0;
        app.loadNotify();
      });
    },
    invokeSave() {
      var app = this;
      if (app.modal_params.type && app.modal_params.type == "post_new") {
        {
          this.$refs.editor._data.state.editor.save()
              .then((data) => {
                // Do what you want with the data here

                var app = this;
                var newPost = app.form;
                newPost.json = data;
                Api.createPost(newPost, function (result) {
                  if (result.iserror == false) {
                    app.iserror = false;
                    app.modals.add_project = false;
                    app.form = {};
                    app.$refs.editor._data.state.editor.clear();
                    //  app.is_load_projects = false;
                    document.location.hash = "#/post/" + result.post_id;
                    app.$router.push({name: 'post', params: {id: result.post_id, "rand": Math.random()}});
                  } else {
                    app.iserror = true;
                    app.error_message = result.error_message;
                  }
                });


                console.log(data)
              })
              .catch(err => {
                console.log(err)
              })
        }
      } else if (app.modal_params.type && app.modal_params.type == "post_edit") {
        {
          this.$refs.editor._data.state.editor.save()
              .then((data) => {
                // Do what you want with the data here

                var app = this;
                var newPost = app.form;
                newPost.json = data;
                newPost.last_id = app.modal_params.post_id;
                Api.createPost(newPost, function (result) {
                  if (result.iserror == false) {
                    app.iserror = false;
                    app.modals.add_project = false;
                    app.form = {};
                    app.$refs.editor._data.state.editor.clear();
                    //  app.is_load_projects = false;
                    document.location.hash = "#/post/" + result.post_id;

                    app.$router.push({name: 'post', params: {id: result.post_id, "rand": Math.random()}});
                  } else {
                    app.iserror = true;
                    app.error_message = result.error_message;
                  }
                });


                console.log(data)
              })
              .catch(err => {
                console.log(err)
              })
        }
      } else if (app.modal_params.type && app.modal_params.type == "edit") {


        this.$refs.editor._data.state.editor.save()
            .then((data) => {
              // Do what you want with the data here
              var edit_project = {"last_id": app.modal_params.project_id, "json": data};
              Api.editProject(edit_project, function () {
                app.iserror = false;
                app.modals.add_project = false;
                eventBus.$emit("update_project_info", {json: edit_project.json})
                {


                }

                app.$refs.editor._data.state.editor.clear();

              }, function (message) {
                app.iserror = true;
                app.error_message = message;
              })
            })
            .catch(err => {
              console.log(err)
            })
      } else if (app.modal_params.type && (app.modal_params.type == "addwork" || app.modal_params.type == "editwork")) {
        this.$refs.editor._data.state.editor.save()
            .then((data) => {
              var newWork = app.modal_params.newWork;
              newWork.json = data;
              Api.addWork(newWork, function () {
                app.iserror = false;
                app.data = [];
                Api.loadUserPortfolio();
                app.config.blocks = null;
                app.modals.add_project = false;
                app.$refs.editor._data.state.editor.clear();
              }, function (message) {
                app.iserror = true;
                app.error_message = message;
              })

            })
            .catch(err => {
              console.log(err)
            })
      } else if (app.modal_params.type && app.modal_params.type == "addtask") {
        this.$refs.editor._data.state.editor.save()
            .then((data) => {
              var edit_project = {"last_id": app.modal_params.project_id, "json": data};
              edit_project.developer = app.new_task_developer;
              Api.addTask(edit_project, function (tasks) {
                app.iserror = false;
                app.modals.add_project = false;
                app.is_add_task = false;
                eventBus.$emit("update_project_tasks", {tasks: tasks})
                {


                }


                app.$refs.editor._data.state.editor.clear();

              }, function (message) {
                app.iserror = true;
                app.error_message = message;
              })
            })
            .catch(err => {
              console.log(err)
            })


      } else {
        this.$refs.editor._data.state.editor.save()
            .then((data) => {
              // Do what you want with the data here

              var app = this;
              var newTask = app.form;
              newTask.json = data;
              Api.createProject(newTask, function (result) {
                if (result.iserror == false) {
                  app.iserror = false;
                  app.modals.add_project = false;
                  app.form = {};
                  app.$refs.editor._data.state.editor.clear();
                  app.is_load_projects = false;
                  document.location.hash = "#/project/" + result.project_id;
                  app.$router.push({name: 'project', params: {id: result.project_id, "rand": Math.random()}});
                } else {
                  app.iserror = true;
                  app.error_message = result.error_message;
                }
              });


              console.log(data)
            })
            .catch(err => {
              console.log(err)
            })
      }


    },
    clickNotify(button, notify, notify_index) {
      console.log('click notify btn');
      console.log(button);
      console.log(notify);
      var app = this;
      Api.setReadNotify(notify.last_id, function () {

        if (app.notify.show_only_unread == true) {
          app.loadNotify();
        }
        if (button.type == "load_invoice") {
          app.$router.push({"name": "invoice", "params": {"id": button.params.invoice_id, "rand": Math.random()}});
          // this.$router.push({"name": "project", "params": {"id": button.params.id, "rand": Math.random()}});
          // vm.$forceUpdate();
        }
        if (button.type == "load_project") {
          app.$router.push({"name": "project", "params": {"id": button.params.id, "rand": Math.random()}});
          // this.$router.push({"name": "project", "params": {"id": button.params.id, "rand": Math.random()}});
          // vm.$forceUpdate();

        }
        if (button.type == "remove_notify") {
          Api.setRemoveNotify(notify.last_id, function () {
            app.loadNotify();
          });
        }
      });

    },
    telegramLoginCallback(user) {

      // gets user as an input
      // id, first_name, last_name, username,
      // photo_url, auth_date and hash
      console.log(user);
      var app = this;
      app.modals.add_project = false;

      axios.post(Vue.config.API_URL + `login`, user)
          .then(response => {

            if (response.data.type == "success") {
              localStorage.setItem('api_token', response.data.user.api_token);
              app.$store.commit('SET_TOKEN', response.data.user.api_token);

              eventBus.$emit('reload', response.data.user);
              this.modal_add_title = "Размещение проекта";
              if (app.show_modal_after_login == true) {
                app.modals.add_project = true;
              } else {
                app.modals.add_project = false;
              }
            } else {
              alert(response.data.message);
            }
          })
          .catch(e => {
            console.log(e);
          })
    }
  }
};
</script>
<style>
</style>

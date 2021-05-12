<template>
  <div>
    <loading :active.sync="is_show_loader"

             :is-full-page="true"></loading>
    <div v-if="is_load">

      <div class="position-relative">
        <!-- shape Hero -->
        <section class="section-shaped my-0">
          <div class="shape shape-style-1 shape-default shape-skew" style="background:url(img/header_bg.jpg);">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
          <div class="container shape-container d-flex">
            <div class="col px-0">
              <div class="row" v-html="about.header">

              </div>
            </div>
          </div>


        </section>
        <!-- 1st Hero Variation -->
      </div>

      <section class="section section-lg">
        <div class="container">
          <div class="row row-grid align-items-center" v-for="category in categorys" :key="category.last_id"
               v-if="categorys.length>0">
            <div class="col-md-6 order-md-2">
              <img :src="image_url+category.image" class="img-fluid floating">
            </div>
            <div class="col-md-6 order-md-1">
              <h2>{{ category.name }}</h2>
              <div class="pr-md-5" v-html="category.description">

              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="section section section-shaped my-0 overflow-hidden">
        <div class="shape shape-style-1 bg-gradient-warning ">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>

        </div>
        <div class="container py-0">
          <div class="row row-grid mb-5 align-items-center">
            <div class="d-flex px-3">
              <div>
                <icon name="ni ni-building" size="lg" class="bg-gradient-white" color="primary" shadow
                      rounded></icon>
              </div>
              <div class="pl-4">
                <h4 class="display-3 text-white">Портфолио</h4>
                <p class="text-white">Тут собраны все работы,которые были выполнены мной</p>
              </div>
            </div>

            <div class="row col-md-12 mt-3">
              <router-link v-for="work in works" :key="work.last_id"
                           v-if="works.length>0"
                           :to="{name: 'workpage', params: { username: work.user.username,work_id:work.last_id }}">
                <sui-card class="col-md-3 m-2">
                  <a href="#">

                    <sui-image :src="work.image"/>
                  </a>
                  <sui-card-content>
                    <sui-card-header><a>{{ work.name }}</a></sui-card-header>
                    <sui-card-meta>
                      <a>{{ work.date_start }}-{{ work.date_end }}</a>
                      <a>{{ work.category.name }}</a>
                    </sui-card-meta>
                  </sui-card-content>
                </sui-card>
              </router-link>


            </div>


          </div>
        </div>
      </section>
      <section class="section section-lg">
        <div class="container">
          <div class="row justify-content-center text-center mb-lg">
            <div class="col-lg-8">
              <h2 class="display-3">Наша команда</h2>
              <p class="lead text-muted">Команда без которой все это не было бы возможно</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-lg-3 mb-5 mb-lg-0" v-for="team in teams" :key="team.last_id"
                 v-if="teams.length>0">
              <div class="px-4">
                <router-link :to="{name: 'developer', params: { username: team.username }}" class="dropdown-item">
                  <avatar v-if="team.avatar==null" :username="team.name" :size="180"
                          class="rounded-circle img-center img-fluid shadow shadow-lg--hover"></avatar>

                  <img v-if="team.avatar!=null" :src="image_url+team.avatar"
                       class="rounded-circle img-center img-fluid shadow shadow-lg--hover"
                       style="width: 200px;">
                </router-link>
                <div class="pt-4 text-center">
                  <h5 class="title">
                    <span class="d-block mb-1">{{ team.name }}</span>
                    <router-link :to="{name: 'developer', params: { username: team.username }}" class="dropdown-item">
                      <small class="h6 text-muted">@{{ team.username }}</small></router-link>
                    <small class="h6 text-muted">{{ team.job }}</small>
                  </h5>
                  <div class="mt-3">
                    <base-button tag="a" :href="'https://t.me/'+team.username" target="_blank" type="warning"
                                 icon="fa fa-telegram" rounded
                                 icon-only></base-button>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>
      <section class="section section section-shaped my-0 overflow-hidden" >
        <div class="shape shape-style-1 bg-gradient-warning ">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>

        </div>
        <div class="container py-0">
          <div class="row row-grid mb-5 align-items-center">
            <div class="d-flex px-3">
              <div>
                <icon name="ni ni-caps-small" size="lg" class="bg-gradient-white" color="primary" shadow
                      rounded></icon>
              </div>
              <div class="pl-4">
                <h4 class="display-3 text-white">Посты</h4>
                <p class="text-white">Последние записи из избранных категорий</p>
              </div>
            </div>

            <div class="row col-md-12 mt-3" v-if="posts && posts.length>0">
              <div class="col-md-4  "
                   v-for="post in posts"
                   :key="post.last_id">
                <div class="row alert alert-secondary ml-1 mb-2 col-md-12">
                  <div class="row col-md-12">
                    <img class="" style="width:30px;"
                         :src="image_url+post.category_post.image"/>
                    <span class="post_blog_category">{{ post.category_post.title }}</span>

                  </div>
                  <div class="row col-md-12">
                    <editorjsrender :blocks_json="post.short.blocks"></editorjsrender>
                  </div>

                  <div class="row col-md-12">

                    <p><i class="fa fa-calendar-times-o"></i> {{ post.created_at }}
                      <router-link class=" " :to="{name: 'post', params: { id: post.last_id }}"
                      ><i class="fa fa-link"></i>
                      </router-link>
                    </p>
                  </div>
                </div>

              </div>


            </div>
            <div class="row col-md-12 mt-3">
              <div class="row ">
                <router-link style="margin-left:2.5rem;" to="/posts" class="btn btn-success" tag="a">Посмотреть все
                  посты
                </router-link>

              </div>
            </div>

          </div>
        </div>
      </section>
      <section class="section section-shaped my-0 overflow-hidden">
        <div class="shape shape-style-3 bg-gradient-default shape-skew">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="container pt-lg pb-300">
          <div class="row text-center justify-content-center">
            <div class="col-lg-10">
              <h2 class="display-3 text-white">Беги к финишу</h2>
              <p class="lead text-white">Следуйте простым шагам и вы достигните результата</p>
            </div>
          </div>
          <div class="row row-grid mt-5">
            <sui-step-group size="mini">
              <a is="sui-step" :active="true" icon="cart plus">
                <sui-step-content
                    title="Опубликуйте заказ"
                    description="Первый шаг к новым ощущениям"

                />
              </a>
              <a is="sui-step" icon="shopping cart">
                <sui-step-content
                    title="Поиск исполнителя"
                    description="Выберите исполнителя который вам понравился :)"

                />
              </a>

              <a is="sui-step" icon="handshake outline">
                <sui-step-content
                    title="Проект в работе"
                    description="Плодотворная работа в самом разгаре"
                />
              </a>
              <a is="sui-step" icon="thumbs up outline">
                <sui-step-content
                    title="Проект завершен"
                    description="Проект завершен, радуйтесь и пляшите"
                />
              </a>
            </sui-step-group>
          </div>
        </div>
      </section>
      <section class="section section-lg pt-lg-0 section-contact-us">
        <div class="container">
          <div class="row justify-content-center mt--300">
            <div class="col-lg-8">
              <card gradient="secondary" shadow body-classes="p-lg-5">
                <h4 class="mb-1">Готовы рассказать о своем проекте?</h4>


                <div class="row">
                  <base-button class="ml-3" type="primary" @click="callModalAddProject">
                    Создать проект <i class="fa fa-plus-circle"></i>
                  </base-button>

                </div>


              </card>
            </div>
          </div>
        </div>
      </section>


    </div>
  </div>
</template>

<script>
import {vueTelegramLogin} from 'vue-telegram-login'
import axios from 'axios';
import Api from "../models/Api";
import Vue from "vue";
import Carousel from "@/views/components/Carousel";
import AppHeader from "@/layout/AppHeader";
import {eventBus} from '../main.js';
import Modal from "@/components/Modal";
import Loading from 'vue-loading-overlay';

import 'vue-loading-overlay/dist/vue-loading.css';
import Editorjsrender from "@/models/Editorjsrender";

var Avatar = require('vue-avatar');


export default {
  name: 'home',

  components: {Loading, Carousel, Modal, vueTelegramLogin, 'avatar': Avatar.Avatar, 'editorjsrender': Editorjsrender,},
  data() {
    return {

      "istgbot": false,
      "is_show_loader": true,
      "telegram_bot": null,
      "islogin": false,
      "config": {},

      slide: 0,
      sliding: null,
      "is_load": false,
      "about": null,
      "posts": null,
      "categorys": null,
      "image_url": null,
      "teams": null,
      "works": null,
      "iserror": false,
      "error_message": null,
      "form": {
        'json': {},
      },
      "loaded_categorys": false,
      "categorys_modal": null,
      "modal_title": "Создать проект",
      'modals': {
        "add": null,
      },
    }
  },
  mounted() {
    //AppHeader.methods["testLog"]();
    var app = this;
    app.telegram_bot = Vue.config.TG_BOT;

    app.istgbot = true;
    app.islogin = false;
    if (app.$store.getters.ISAUTH) {
      var tmpUser = app.$store.getters.USER;

      app.user = tmpUser;
      app.islogin = true;
      app.loadInfoAfterLogin();

    }

    eventBus.$on('user_is_login', user => {
      this.islogin = false;
      if (typeof user === 'object') {
        if (user.api_token) {
          app.user = user;
          app.islogin = true;
          app.loadInfoAfterLogin();
        }
      }


    });

    console.log(this);
    Api.loadAbout(function (data) {
      app.about = data.about;
      app.posts = data.posts;
      app.categorys = data.categorys;

      app.image_url = Vue.config.IMAGE_URL;
      app.teams = data.teams;
      app.works = data.works;
      app.is_load = true;
      app.is_show_loader = false;
    })
  },
  methods: {
    callModalAddProject() {
      eventBus.$emit("call_modal_add_project", {})
      {

      }
    },
    onSlideStart(slide) {
      this.sliding = true
    },
    onSlideEnd(slide) {
      this.sliding = false
    },
    loadInfoAfterLogin() {
      var app = this;
      Api.loadCategorys(function (data) {
        app.categorys_modal = data;
        app.loaded_categorys = true;
      });

      app.config = {
        placeholder: 'Распишите задачу как можно подробнее',
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
              endpoint: Vue.config.API_URL + 'editor/uploadFile?api_token=' + app.$store.getters.TOKEN,
            }
          },
          image: {
            class: require('@editorjs/image'),
            config: {
              additionalRequestHeaders: {
                "Authorization": 'Bearer ' + app.$store.getters.TOKEN
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
        }
      };

    },
    invokeSave() {
      var app = this;

      this.$refs.editor._data.state.editor.save()
          .then((data) => {
            // Do what you want with the data here

            var app = this;
            var newTask = app.form;
            newTask.json = data;
            Api.createProject(newTask, function (result) {
              if (result.iserror == false) {
                app.iserror = false;
                app.modals.add = false;
                app.form = {};
                app.$refs.editor._data.state.editor.clear();
                app.is_load_projects = false;
                // console.log('answer create project');
                // console.log(result);
                document.location.hash = "#/project/" + result.project_id;
                app.$router.push({name: 'project', params: {id: result.project_id}});

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
    },
    addProject() {
      this.$refs.editor._data.state.editor.render(
          {
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
          });
      this.form.category = null;

      this.modal_title = "Создание проекта";
      this.modals.add = true;


    },
  }
};
</script>

<template>
  <div>
    <div>
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
              <div class="row">
                <h1 class="display-3  text-white">Все посты

                </h1>


              </div>
              <div class="row">
                <button type="danger" class="btn btn-danger" v-if="islogin" @click="callWritePost">Написать пост
                </button>
              </div>


            </div>
          </div>
        </section>
        <!-- 1st Hero Variation -->
        <section class="section section  overflow-hidden" v-if="posts.length>0">

          <div class="container py-0">
            <div class="row row-grid mb-5 align-items-center">


              <div class="row col-md-12 mt-3 ">

                <div class="row col-md-12"
                     v-for="post in posts"
                     :key="post.last_id">
                  <div class="row col-md-12 alert alert-secondary  mb-2">
                    <div class="col-md-12">
                      <img class="" style="width:30px;height:30px;"
                           :src="image_url+post.category_post.image"/>
                      <span class="post_blog_category">{{ post.category_post.title }}</span>

                    </div>
                    <div class="col-md-12 ">
                      <editorjsrender :blocks_json="post.short.blocks"></editorjsrender>
                    </div>

                    <div class="col-md-12 mt-2">

                      <p><i class="fa fa-calendar-times-o"></i> {{ post.created_at }}
                        <router-link class="btn btn-success " :to="{name: 'post', params: { id: post.last_id }}"
                        >Посмотреть
                        </router-link>
                      </p>

                    </div>
                  </div>

                </div>


              </div>
              <div class="row col-md-12 mt-3" v-if="count>current">
                <div class="row ">
                  <button class="btn btn-default" @click="loadPosts">Загрузить еще</button>

                </div>
              </div>

            </div>
          </div>
        </section>

      </div>
    </div>
  </div>
</template>

<script>
import {eventBus} from "@/main";
import Editorjsrender from "../../models/Editorjsrender.vue";
import Api from "@/models/Api";
import Vue from "vue";

export default {
  name: "Posts",
  data: function () {
    return {
      user: null,
      islogin: false,
      current: 0,
      count: 0,
      posts: [],
      image_url: null,
    }
  },
  mounted() {
    var app = this;
    app.image_url = Vue.config.IMAGE_URL;
    if (app.$store.getters.ISAUTH) {
      var tmpUser = app.$store.getters.USER;

      app.user = tmpUser;
      app.islogin = true;


    }

    eventBus.$on('user_is_login', user => {
      this.islogin = false;
      if (typeof user === 'object') {
        if (user.api_token) {
          app.user = user;
          app.islogin = true;

        }
      }


    });
    app.loadPosts();
  },
  methods: {
    loadPosts() {
      var app = this;
      Api.loadPosts(20, app.current, function (data) {
        app.count = data.count;
        app.posts = app.posts.concat(data.posts);
        app.current = app.current + data.posts.length;
        console.log('current');
        console.log(app.current);
        console.log('count');
        console.log(app.count);

      })
    },
    callWritePost() {
      var params = {

        type: "post_new",

        modal_title: "Добавить пост",

        json: {
          "blocks": [{
            "type": "header",
            "data": {
              "text": "Название вашего поста",
              "level": 2
            }
          }]
        }
      };
      eventBus.$emit("call_modal_add_project", params)
      {


      }
    }
  },
  components: {
    'editorjsrender': Editorjsrender,
  }
}
</script>

<style scoped>

</style>
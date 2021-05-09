<template>
  <div class="profile-page" v-if="is_load">
    <section class="section-profile-cover section-shaped my-0">
      <div class="shape shape-style-1 shape-primary shape-skew alpha-4"

           v-bind:style="{ background: 'url('+background_header +') !important' }"

      >

        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>

      </div>
      <div class="container shape-container d-flex ">
        <div class="col px-0">
          <div class="row m-3 alert alert-danger">
            <img class="" style="width:30px;"
                 :src="image_url+post_data.category_post.image"/>
            <span class="ml-2 pt-2 post_blog_category">{{ post_data.category_post.title }}</span>

          </div>

        </div>
      </div>
    </section>
    <section class=" section section-skew
      ">
      <div class="container">

        <card shadow class="card-profile mt--300" no-body>
          <h2 class="mt-2 mb-2 ml-2">
            <router-link
                to="/posts">

              Назад
            </router-link>
          </h2>
          <div class="row mt-3 ml-3">
            <div class="col-md-2 ">
              <router-link v-if="post_data.user.isdeveloper==1"
                           :to="{name: 'developer', params: { username: post_data.user.username }}">
                <img v-if="post_data.user.avatar" :src="image_url+post_data.user.avatar" width="100px"
                     class="rounded-circle">

              </router-link>

              <router-link v-if="post_data.user.isdeveloper==0"
                           to="/">
                <img v-if="post_data.user.avatar" :src="image_url+post_data.user.avatar" width="100px"
                     class="rounded-circle">

              </router-link>
            </div>
            <div class="col-md-6">
              <h3>{{ post_data.user.name }}

              </h3>
              <div class="h6 font-weight-300" v-if="post_data.user.isdeveloper==1"><a
                  :href="'https://t.me/'+post_data.user.username" class="btn btn-success" target="_blank"><i
                  class="fa fa-lg fa-telegram"></i>{{ post_data.user.username }}</a>
              </div>


            </div>
          </div>
          <div class="container container-lg">


            <editorjsrender class="ml-3" v-if="is_load " :blocks_json="post_data.content.blocks"></editorjsrender>
            <div class="row m-3">

              <p><i class="fa fa-calendar-times-o"></i> {{ post_data.created_at }} </p>
            </div>
            <div class="row m-3" v-if="user && user.last_id==post_data.user.last_id">
              <p>
                <button class="btn btn-danger" @click="callEditPost()">Редактировать</button>

                <button class="btn btn-warning" @click="toogleEnable" v-if="post_data.enable==1">В черновик</button>
                <button class="btn btn-warning" @click="toogleEnable" v-if="post_data.enable==0">Опубликовать</button>
              </p>
            </div>

            <div class="row m-3">
              <sui-comment-group size="massive" threaded>
                <h3 is="sui-header" dividing>Комментарии</h3>

                <sui-comment v-if="post_data.comments.length>0" v-for="(comment,comment_index) in post_data.comments"
                             :key="comment.last_id">
                  <sui-comment-avatar v-if="comment.user.avatar" :src="image_url+comment.user.avatar"/>
                  <div class="avatar" v-if="comment.user.avatar==null">

                    <avatar v-if="comment.user.avatar==null" :size="35" :username="comment.user.name"
                            class="rounded-circle"></avatar>

                  </div>


                  <sui-comment-content>
                    <a is="sui-comment-author">{{ comment.user.name }}</a>
                    <sui-comment-metadata>
                      <div>{{ comment.created_at }}</div>
                      <div v-if="post_data.my_access && post_data.my_access.isadmin==1">
                        <button @click="deleteComment(comment.last_id,comment_index)"><i class="fa fa-remove"></i>
                        </button>
                      </div>


                    </sui-comment-metadata>
                    <sui-comment-text>{{ comment.comment }}</sui-comment-text>

                  </sui-comment-content>
                  <sui-comment-group>

                    <sui-comment v-for="(subcomment,index_subcomment) in comment.comments"
                                 :key="subcomment.last_id">
                      <sui-comment-avatar v-if="subcomment.user.avatar" :src="image_url+subcomment.user.avatar"/>
                      <div class="avatar" v-if="subcomment.user.avatar==null"
                      >

                        <avatar v-if="subcomment.user.avatar==null" :size="35" :username="subcomment.user.name"
                                class="rounded-circle"></avatar>

                      </div>
                      <sui-comment-content>
                        <a is="sui-comment-author">{{ subcomment.user.name }}</a>
                        <sui-comment-metadata>
                          <div>{{ subcomment.created_at }}</div>
                          <div v-if="post_data.my_access && post_data.my_access.isadmin==1">
                            <button
                                @click="deleteSubComment(subcomment.last_id,comment.last_id,index_subcomment,comment_index)">
                              <i
                                  class="fa fa-remove"></i></button>
                          </div>
                        </sui-comment-metadata>
                        <sui-comment-text>
                          {{ subcomment.comment }}
                        </sui-comment-text>

                      </sui-comment-content>
                    </sui-comment>
                    <sui-comment v-if="islogin">
                      <div class="row">
                        <div class="col-md-8">
                          <textarea class="row form-control"
                                    v-model="post_subcomment['comment'+comment.last_id]"></textarea>
                        </div>
                        <div class="col-md-4">
                          <button class="btn btn-success" @click="sendSubComment(comment.last_id,comment_index)"><i
                              class="fa fa-arrow-circle-right"></i></button>
                        </div>
                      </div>
                    </sui-comment>
                  </sui-comment-group>
                </sui-comment>


                <h3 v-if="islogin" is="sui-header" dividing>Написать комментарий</h3>
                <div class="alert alert-danger" v-if="new_comment_is_error==true">
                  <p>{{ new_comment_error }}</p>
                </div>


                <div class="row pt-2 pb-2" v-if="islogin">
                  <div class="col-md-8">
                    <label for="exampleTitle">Комментарий </label>
                    <input type="text" class="form-control" required id="exampleTitle" v-model="newComment.comment"
                           placeholder="Введите комментарий">
                  </div>
                  <div class="col-md-4">
                    <label for="exampleTitle"></label>
                    <button class=" btn btn-success" @click="sendComment">Отправить комментарий</button>
                  </div>
                </div>


              </sui-comment-group>
            </div>
          </div>
        </card>
      </div>
    </section>
  </div>
</template>

<script>
import Api from "@/models/Api";
import Vue from "vue";
import Editorjsrender from "@/models/Editorjsrender";
import {eventBus} from "@/main";

export default {
  name: "Post",
  data() {
    return {
      is_load: false,
      post_data: null,
      image_url: null,
      background_header: "img/header_bg.jpg",
      user: null,
      islogin: false,
      "new_comment_is_error": false,
      "new_comment_error": null,
      post_subcomment: [],
      "newComment": {
        "comment": null
      },

    }
  },
  mounted() {
    //AppHeader.methods["testLog"]();
    var app = this;
    app.image_url = Vue.config.IMAGE_URL;
    let id = app.$route.params.id;

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
    Api.loadPost(id, function (data) {
      app.post_data = data;

      if (data.category_post.background && data.category_post.background.length > 0) {

        app.background_header = app.image_url + data.category_post.background;

      }
      app.is_load = true;
    })

  },
  methods: {
    toogleEnable() {
      var app = this;
      Api.changeEnablePost(app.post_data.last_id, function (data) {
        app.post_data = data;
      })
    },
    deleteComment(comment_id, index_comment) {
      var app = this;
      Api.deleteCommentPost(comment_id, app.post_data.last_id, function (data) {
        //   app.post_data.comments = app.post_data.comments.slice();
        //
        app.post_data.comments.splice(index_comment, 1);
      })
    },
    deleteSubComment(subcomment_id, comment_id, index_sub, index_comment) {
      var app = this;
      Api.deleteSubCommentPost(subcomment_id, comment_id, app.post_data.last_id, function (data) {
        //   app.post_data.comments = app.post_data.comments.slice();
        //
        app.post_data.comments[index_comment].comments.splice(index_sub, 1);
      })
    },
    sendSubComment(comment_id, comment_index) {
      var app = this;
      console.log('send comment');
      var comment = app.post_subcomment['comment' + comment_id];
      Api.sendPostSubComment(app.post_data.last_id, comment_id, comment, function (comments) {
        app.post_data.comments[comment_index].comments = comments;
        app.post_subcomment['comment' + comment_id] = "";
      });
    },
    sendComment(event) {
      event.preventDefault();
      var app = this;
      var newComment = app.newComment;
      Api.addPostComment(newComment, app.post_data.last_id, function (result_comment) {
        app.iserror = false;

        app.newComment.comment = "";
        app.post_data.comments.push(result_comment)
      }, function (message) {
        app.iserror = true;
        app.error_message = message;
      })


    },
    callEditPost() {
      var app = this;
      var params = {

        type: "post_edit",
        post_id: app.post_data.last_id,
        modal_title: "Редактировать пост",
        category: app.post_data.category,
        json: app.post_data.content
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
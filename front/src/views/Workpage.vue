<template>
  <div class="profile-page" v-if="isload">
    <section class="section-profile-cover section-shaped my-0">
      <div class="shape shape-style-1 shape-primary shape-skew alpha-4"
           style="background: url(img/header_bg.jpg) !important;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </section>
    <section class="section section-skew">
      <div class="container">
        <card shadow class="card-profile mt--300" no-body>
          <div class="row mt-3 ml-3">
            <div class="col-md-2 ">
              <router-link
                  :to="{name: 'developer', params: { username: developer.username }}">
                <img v-if="developer.avatar" :src="image_url+developer.avatar" width="100px" class="rounded-circle">
                <avatar v-if="developer.avatar==null" :size="100" :username="developer.name"
                ></avatar>
              </router-link>

            </div>
            <div class="col-md-6">
              <h3>{{ developer.name }}

              </h3>
              <div class="h6 font-weight-300"><a
                  :href="'https://t.me/'+developer.username" class="btn btn-success" target="_blank"><i
                  class="fa fa-lg fa-telegram"></i>{{ developer.username }}</a></div>
            </div>
          </div>
          <div class="container container-lg">
            <div class="row m-3">
              <router-link :to="{name: 'developer', params: { username: this.developer.username }}">
                <base-button class="m-2" type="primary">Все работы</base-button>
              </router-link>
              <router-link :to="{name: 'reviewpage', params: { username: this.developer.username }}">
                <base-button class="m-2" type="secondary">Отзывы</base-button>
              </router-link>
            </div>

            <div class="alert alert-success m-3" role="alert">
              <h4 class="alert-heading">Информация о работе</h4>
              <p>Категория:{{ work.category.name }}</p>
              <p>Дата {{ work.date_start }} - {{ work.date_end }}</p>

            </div>

            <editorjsrender v-if="isload " :blocks_json="work.json.blocks"></editorjsrender>
          </div>
        </card>
      </div>
    </section>
  </div>
</template>
<script>
import {eventBus} from "@/main";
import Vue from "vue";
import Api from "../models/Api";
import Editorjsrender from "../models/Editorjsrender.vue";

export default {
  name: "developer",
  data() {
    return {
      'more_btn': '<button  class="btn btn-success">Посмотреть работу</button>',
      "isload": false,
      'developer': {},
      'work': {},
      'image_url': null,

    }
  },
  mounted() {
    var app = this;
    Api.loadDeveloperWork(this.$route.params.username, this.$route.params.work_id, function (developer_data, work_data) {
      app.image_url = Vue.config.IMAGE_URL;
      app.developer = developer_data;
      app.work = work_data;
      console.log('work data');
      console.log(app.work);

      app.isload = true;
    });

  },
  methods: {},
  components: {
    'editorjsrender': Editorjsrender,
  }
};
</script>
<style>
</style>

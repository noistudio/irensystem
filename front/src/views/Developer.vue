<template>
  <div class="profile-page" v-if="isload">
    <section class="section-profile-cover section-shaped my-0">
      <div class="shape shape-style-1 shape-primary shape-skew alpha-4" style="background: url(img/header_bg.jpg) !important;">
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
              <a href="#" class="ml-3">
                <img v-if="developer.avatar" :src="image_url+developer.avatar" width="100px" class="rounded-circle">
                <avatar v-if="developer.avatar==null" :size="100" :username="developer.name"
                ></avatar>
              </a>

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
              <base-button class="m-2" type="primary">Все работы</base-button>
              <router-link :to="{name: 'reviewpage', params: { username: this.developer.username }}">
                <base-button class="m-2" type="secondary">Отзывы</base-button>
              </router-link>
            </div>
            <div class="row m-3">


              <vue-timeline-update @click="clickWork(work)" v-for="work in developer.portfolio" :key="work.last_id"
                                   :date="new Date(work.date_start)"
                                   :title="work.name"
                                   :description="work.description+'<br>'+more_btn"
                                   :thumbnail="work.image"
                                   :category="work.category.name"
                                   icon="code"
                                   color="red"
              />


            </div>
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
import Modal from "@/components/Modal";
import Editorjsrender from "@/models/Editorjsrender";

var Avatar = require('vue-avatar')

export default {
  name: "developer",
  data() {
    return {
      'more_btn': '<button  class="btn btn-success">Посмотреть работу</button>',
      "isload": false,
      'developer': {},
      'image_url': null,

    }
  },
  mounted() {
    Api.loadDeveloper(this.$route.params.username);
    eventBus.$on('load_developer_user', developer_data => {
      this.image_url = Vue.config.IMAGE_URL;
      this.developer = developer_data;

      this.isload = true;


    });
  },
  methods: {
    clickWork(work) {
      console.log('event click ');
      console.log(work.last_id);
      this.$router.push({name: 'workpage', params: {username: this.developer.username, "work_id": work.last_id}});
    }
  },
  components: {


    'avatar': Avatar.Avatar
  }
};
</script>
<style>
</style>

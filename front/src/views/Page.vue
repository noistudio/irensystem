<template>
  <div>

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
              <div class="row">
                <h1 class="display-3  text-white">{{ page_data.title }}

                </h1>
              </div>
            </div>
          </div>
        </section>
        <!-- 1st Hero Variation -->
      </div>

      <section class="section section-lg">
        <div class="container" v-html="page_data.content">

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

var Avatar = require('vue-avatar');


export default {
  name: 'home',

  components: {Loading, Carousel, Modal, vueTelegramLogin, 'avatar': Avatar.Avatar},
  data() {
    return {
      is_load: false,
      page_data: null,


    }
  },
  mounted() {
    //AppHeader.methods["testLog"]();
    var app = this;
    let id = app.$route.params.id;

    eventBus.$on('user_is_login', user => {
      this.islogin = false;
      if (typeof user === 'object') {
        if (user.api_token) {
          app.user = user;
          app.islogin = true;

        }
      }


    });

    Api.loadPage(id, function (data) {
      app.page_data = data;
      app.is_load = true;


    });

  },
  methods: {}
};
</script>

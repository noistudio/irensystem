<template>
  <div v-if="isload">
    <div class="position-relative">
      <!-- shape Hero -->
      <section class="section-shaped my-0">
        <div class="shape shape-style-1 shape-default shape-skew"
             style="background: url(img/header_bg.jpg) !important;">
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
              <div class="col-lg-6">
                <h1 class="display-3  text-white">Настройки профиля
                  <span>тут вы можете загрузить аватарку и вписать свои реквизиты :)</span>
                </h1>

              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- 1st Hero Variation -->


      <div class="row ml-5 mb-5">
        <div class="col-md-5">
          <div class="row ml-5 mb-5" v-if="isload_avatar">
            <img :src="image_url+avatar" class="col-md-4 center-block img img-thumbnail-avatar">
          </div>
          <div class="row ml-5 mb-5">
            <vue-dropzone @vdropzone-success="vdropzone_complete($event)" ref="myVueDropzone" id="dropzone"
                          :options="dropzoneOptions"></vue-dropzone>
          </div>
        </div>
        <div class="col-md-5">
          <div class="alert alert-danger" v-if="iserror==true">
            <p>{{ error_message }}</p>
          </div>
          <form @submit.prevent="onSubmit($event)">
            <div class="form-group">
              <label for="exampleInputEmail1">Реквизиты </label>
              <textarea class="form-control" required id="exampleInputEmail1" v-model="account"
                        placeholder="Введите реквизиты"></textarea>


              <button type="submit" class="mt-3 btn btn btn-primary">Сохранить
              </button>

            </div>
          </form>
        </div>

      </div>

    </div>


  </div>
</template>
<script>

import {eventBus} from "@/main";
import axios from 'axios';
import Vue from "vue";
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import Api from "../models/Api";

export default {
  name: "home",

  data() {
    var app = this;


    return {
      "iserror": false,
      "error_message": null,
      'account': '',
      'avatar': "",
      "isload_avatar": false,
      "user": null,
      'image_url': null,
      'isload': false,
      dropzoneOptions: {
        url: Vue.config.API_URL + 'uploadavatar',
        thumbnailWidth: 150,
        maxFilesize: 0.5,
        dictDefaultMessage: "<i class='fa fa-cloud-upload'></i>ГРУЗИ АВАТАРКУ СЮДА!",
        headers: {"Authorization": "Bearer " + app.$store.getters.TOKEN},


      }
    }
  },
  mounted() {
    var app = this;
    app.isload = false;
    app.account = '';
    // Код, который будет запущен только после
    // отображения всех представлений

    console.log('setup check');
    console.log(app.$store);
    if (app.$store.getters.USER) {
      var tmp_user = app.$store.getters.USER;
      app.isload = true;
      app.user = tmp_user;
      app.image_url = Vue.config.IMAGE_URL;

      //  console.log('setup data');
      //  console.log(this.$store.getters.USER);
      if (app.user.account) {
        app.account = app.user.account;
      }
      if (app.user.avatar) {
        app.avatar = app.user.avatar;
        app.isload_avatar = true;
      }
    }


  },

  methods: {
    onSubmit(event) {
      event.preventDefault();
      var app = this;
      Api.setAccount(app.account, function () {
        app.iserror = false;
      }, function (message) {
        app.iserror = true;
        app.error_message = message;
      })


    },
    vdropzone_complete(file) {
      console.log('resp la la la');
      //  console.log(file);
      var response = JSON.parse(file.xhr.response);
      console.log(response.files.file);
      this.avatar = response.files.file;
    }
  },
  components: {vueDropzone: vue2Dropzone}
};
</script>
<template>
  <div class="profile-page" v-if="is_load">
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
            <div class="col-md-8 ">
              <h4>Счет #{{ invoice.last_id }}</h4>
              <div class="h6 font-weight-300"><i class="fa fa-calendar mr-2"></i>{{ invoice.created_at }}

              </div>
              <div class="h6 font-weight-300">
                <p>Описание</p>
                <p>{{ invoice.title }}</p>
              </div>

              <h3>Сумма счета {{ invoice.sum }} <i class="fa fa-rub" v-if="invoice.currency=='RUB'"></i> <i
                  class="fa fa-usd"
                  v-if="invoice.currency=='USD'"></i>
                <i class="fa fa-euro" v-if="invoice.currency=='EURO'"></i></h3>
              <div class="row alert alert-success" v-if="invoice.developer.account && invoice.ispay==0">
                <p>Реквизиты:</p>
                <p>&nbsp;</p>
                <p>{{ invoice.developer.account }}</p>

              </div>
              <div class="row alert alert-danger" v-if="invoice.developer.account==null && invoice.ispay==0">
                <p>Исполнитель не заполнил реквизиты, сообщите ему об этом</p>


              </div>


            </div>
            <div class="col-md-4">
              <base-button v-if="type=='client' && invoice.client_pay==0" @click="invoiceSetPay()" type="info" size="sm"
                           class="mr-4">Я оплатил счет
              </base-button>

              <base-button v-if="type=='developer' && invoice.client_pay==1 && invoice.ispay==0"
                           @click="invoiceFinishPay()" type="default" size="sm"
                           class="mr-4">Я получил деньги
              </base-button>

              <badge v-if="type=='developer' && invoice.ispay==1" type="info">счет оплачен</badge>
            </div>

          </div>

          <div class="col-md-10 m-5"
               v-if="invoice.ispay==1 &&((type=='developer' && invoice.developer_is_review==0) || (type=='client' && invoice.client_is_review==0) )">

            <h3 is="sui-header" dividing>Оставить отзыв</h3>
            <div class="alert alert-danger" v-if="is_error==true">
              <p>{{ error_message }}</p>
            </div>

            <div class="row">
              <div class="col-md-6">
                          <textarea class="row form-control"
                                    v-model="newreview.review"></textarea>
              </div>

              <div class="form-group col-md-4">

                <sui-rating :rating="newreview.rating" @rate="changeRate" :max-rating="5"/>


              </div>

              <div class="col-md-2">
                <button class="btn btn-success" @click="sendReview"><i
                    class="fa fa-arrow-circle-right"></i></button>
              </div>
            </div>

          </div>


        </card>
      </div>
    </section>
  </div>
</template>
<script>
import {eventBus} from "@/main";
import axios from "axios";
import Vue from "vue";
import Api from "../models/Api";

export default {
  data() {
    return {
      "type": "client",
      'invoice': null,
      'is_load': false,
      "newreview": {
        "review": null,
        "rating": null,
      },
      "is_error": false,
      "error_message": null,


    }
  },
  methods: {
    invoiceFinishPay() {
      var app = this;
      var id = app.invoice.last_id
      Api.invoiceFinishPay(id, function () {
        app.invoice.ispay = 1;
      })

    },
    sendReview() {
      var app = this;
      Api.sendReview(app.newreview, app.invoice.last_id, function (data) {
        app.invoice = data.invoice;
      }, function (message) {
        app.is_error = true;
        app.error_message = message;
      })
    },
    changeRate(evt, props) {
      this.newreview.rating = props.rating;
    },
    invoiceSetPay() {

      var app = this;
      var id = app.invoice.last_id
      Api.invoiceSetPay(id, function () {
        app.invoice.client_pay = 1;
      });

    }
  },

  mounted() {
    var app = this;
    let id = app.$route.params.id;
    Api.loadInvoice(id);
    eventBus.$on('is_load_invoice', data => {


      app.user = data.user;
      app.invoice = data.invoice;
      if (app.user.last_id == data.invoice.developer_id) {
        app.type = "developer";
      }
      if (app.user.last_id == data.invoice.client_id) {
        app.type = "client";
      }
      app.is_load = true;


    });
  }
}
</script>
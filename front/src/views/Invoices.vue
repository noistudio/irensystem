<template>

  <!-- 1st Hero Variation -->
  <section class="section section-shaped section-lg my-0">
    <div class="shape shape-style-2 bg-gradient-default" style="background: url(img/header_bg.jpg) !important;">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
    <div class="container ">
      <div class="row">
        <div class=" col-lg-6 justify-content-center">
          <card type="secondary" shadow
                header-classes="bg-white pb-5"
                body-classes=""
                class="border-0">
            <div class="col-md-12 center-block" v-if="is_load_invoice">

              <div
                  v-if="invoice_index>=(((invoices_table.current_page*invoices_table.on_page)-invoices_table.on_page)-1) && invoice_index<=((invoices_table.current_page*invoices_table.on_page)-1)"
                  v-for="(invoice,invoice_index) in invoices" :key="invoice.invoice_id">
                <div class="row mt-2" v-if="invoice.client_id==user.last_id">
                  <div class="col-lg-3">
                    <router-link :to="{name: 'invoice', params: { id: invoice.last_id }}"
                                 class="mb-3 mb-sm-0 btn btn-danger">
                      {{ invoice.last_id }}
                    </router-link>
                  </div>
                  <div class="col-lg-5">


                    {{ invoice.sum }} <i class="fa fa-rub" v-if="invoice.currency=='RUB'"></i> <i class="fa fa-usd"
                                                                                                  v-if="invoice.currency=='USD'"></i>
                    <i class="fa fa-euro" v-if="invoice.currency=='EURO'"></i>
                  </div>
                  <div class="col-lg-4">

                    <badge v-if="invoice.client_pay==0 && invoice.ispay==0 " pill type="danger">Необходимо оплатить
                    </badge>
                    <badge v-if="invoice.client_pay==1 && invoice.ispay==0" pill type="danger">Ожидаем подтверждение
                    </badge>
                    <badge v-if="invoice.ispay==1" pill type="success">Счет оплачен</badge>
                  </div>
                </div>

                <div class="row mt-2" v-if="invoice.developer_id==user.last_id">
                  <div class="col-lg-3">
                    <router-link :to="{name: 'invoice', params: { id: invoice.last_id }}"
                                 class="mb-3 mb-sm-0 btn btn-danger">
                      #{{
                        invoice.last_id
                      }}
                    </router-link>
                  </div>
                  <div class="col-lg-5">
                    {{ invoice.sum }} <i class="fa fa-rub" v-if="invoice.currency=='RUB'"></i> <i class="fa fa-usd"
                                                                                                  v-if="invoice.currency=='USD'"></i>
                    <i class="fa fa-euro" v-if="invoice.currency=='EURO'"></i>
                  </div>
                  <div class="col-lg-4">

                    <badge v-if="invoice.client_pay==0 && invoice.ispay==0 " pill type="info">Ждем денег</badge>
                    <badge v-if="invoice.client_pay==1 && invoice.ispay==0" pill type="primary">Клиент отправил денег
                    </badge>
                    <badge v-if="invoice.ispay==1" pill type="success">Счет оплачен</badge>
                  </div>
                </div>
              </div>
              <div class="row mt-3" v-if="invoices.length>0">
                <base-pagination :per-page="invoices_table.on_page" :total="invoices.length"
                                 v-model="invoices_table.current_page"></base-pagination>
              </div>
            </div>
            <div class="col-md-12 center-block" v-if="is_load_invoice==false">
              <sui-segment>
                <sui-dimmer active>
                  <sui-loader size="tiny"></sui-loader>
                </sui-dimmer>


              </sui-segment>
            </div>
          </card>
        </div>
        <div class="col-lg-6">

          <card type="secondary" shadow
                header-classes="bg-white pb-5"
                body-classes=""
                class="border-0">
            <div class="row m-2">
              <h3 is="sui-header" dividing>Статистика</h3>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div v-if="is_load_chart">
                  <BarChart :chartdata="circle_chart_rub_developer.chartdata"
                            :options="circle_chart_rub_developer.options"/>
                </div>
              </div>
              <div class="col-md-6">
                <div v-if="is_load_chart">
                  <BarChart :chartdata="circle_chart_rub_client.chartdata"
                            :options="circle_chart_rub_client.options"/>
                </div>
              </div>
            </div>


          </card>
        </div>

      </div>
    </div>
  </section>

</template>

<script>
import {eventBus} from "@/main";
import wysiwyg from "vue-wysiwyg";
import Modal from "@/components/Modal";
import axios from "axios";
import Vue from "vue";
import Api from "../models/Api";
import CircleChart from "@/models/CircleChart";
import BarChart from "@/models/BarChart";

export default {
  data() {
    return {
      "invoices_table": {
        "current_page": 1,
        "on_page": 10
      },
      'is_load_chart': false,
      'circle_chart_rub_developer': {
        chartdata: {
          labels: ['Получено', 'Ждем денег',],
          datasets: [
            {
              label: '₽',
              backgroundColor: ['#03acca', '#ff3709'],
              data: [0, 0]
            },
            {
              label: '$',
              backgroundColor: ['#109D25', '#CA8D24',],
              data: [0, 0]
            },
            {
              label: '€',
              backgroundColor: ['#CA24C5', '#460569',],
              data: [0, 0]
            },

          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: true,
            text: 'Исполнитель'
          }
        }
      },
      'circle_chart_rub_client': {
        chartdata: {
          labels: ['Оплачено', 'Необходимо оплатить',],
          datasets: [
            {
              label: '₽',
              backgroundColor: ['#03acca', '#ff3709'],
              data: [0, 0]
            },
            {
              label: '$',
              backgroundColor: ['#109D25', '#CA8D24',],
              data: [0, 0]
            },
            {
              label: '€',
              backgroundColor: ['#CA24C5', '#460569',],
              data: [0, 0]
            },

          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: true,
            text: 'Клиент'
          }
        }
      },

      'is_load_invoice': false,
      'user': {},
      'invoices': [],
    }
  },
  mounted() {
    var app = this;
    Api.loadInvoices();
    Api.loadStatsInvoices(function (stats) {


      app.circle_chart_rub_developer.chartdata.datasets[0].data = [stats.rub_developer.pay, stats.rub_developer.notpay];
      app.circle_chart_rub_developer.chartdata.datasets[1].data = [stats.usd_developer.pay, stats.usd_developer.notpay];
      app.circle_chart_rub_developer.chartdata.datasets[2].data = [stats.euro_developer.pay, stats.euro_developer.notpay];

      app.circle_chart_rub_client.chartdata.datasets[0].data = [stats.rub_client.pay, stats.rub_client.notpay];
      app.circle_chart_rub_client.chartdata.datasets[1].data = [stats.usd_client.pay, stats.usd_client.notpay];
      app.circle_chart_rub_client.chartdata.datasets[2].data = [stats.euro_client.pay, stats.euro_client.notpay];


      app.is_load_chart = true;
    })
    eventBus.$on('is_loaded_invoices', data => {
      console.log('is user load');
      console.log(data.user);
      app.is_load_invoice = true;
      app.user = data.user;
      app.invoices = data.invoices;


    });
  },
  components: {CircleChart, BarChart}
}
</script>
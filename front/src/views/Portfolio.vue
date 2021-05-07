<template>
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
              <div class="col-lg-6">
                <h1 class="display-3  text-white">Портфолио
                  <span>разместите свое портфолио и оно появится в вашем профиле</span>
                </h1>

              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- 1st Hero Variation -->


      <div class="row ">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-10 ">

          <div>
            <base-button type="primary" @click="addWork()">
              Добавить работу
            </base-button>


          </div>


          <b-table @cellclick="(row,rowIndex,column)=>cellclick(row,rowIndex,column)" :selected.sync="selected"
                   :data="data">
            <b-table-column field="last_id" label="ID" width="40" numeric v-slot="props">
              {{ props.row.last_id }}
            </b-table-column>
            <b-table-column field="category.name" label="Категория" width="150" numeric v-slot="props">
              {{ props.row.category.name }}
            </b-table-column>
            <b-table-column field="name" label="Название работы" width="150" numeric v-slot="props">
              {{ props.row.name }}
            </b-table-column>

            <b-table-column field="created_at" label="Дата" width="150" numeric v-slot="props">
              {{ props.row.created_at }}
            </b-table-column>
            <b-table-column custom-key="actions_edit"
            >
              <button
                  class="btn btn-warning">
                Редактировать
              </button>
            </b-table-column>
            <b-table-column custom-key="actions_delete"
                            class="has-text-right">
              <button
                  class="btn btn-danger">
                Удалить
              </button>
            </b-table-column>

          </b-table>
        </div>

      </div>

    </div>


  </div>
</template>
<script>

import {eventBus} from "@/main";
import axios from 'axios';
import Vue from "vue";
import Modal from "@/components/Modal";
import Api from "../models/Api";


export default {
  name: "home",

  data() {
    var app = this;
    return {
      "modal_title": "Добавить работу",
      "iserror": false,
      "error_message": null,
      "newWork": {
        "category": null,
        'json': null,
        'date_start': null,
        'date_end': null
      },
      "config": {
        placeholder: 'Напиши о своем заебатом проекте тут!',
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
      },
      "loaded_categorys": false,
      "categorys": null,
      'modals': {
        "add": null,
      },
      data: [],
      selected: null,
      defaultOpenedDetails: [],
      showDetailIcon: true,

    }
  },

  mounted: function () {
    Api.loadPortfolioCategorys();
    Api.loadUserPortfolio();

    eventBus.$on('load_portfolio_user', portfolio_rows => {
      this.data = portfolio_rows;


    });

    eventBus.$on('load_portfolio_categorys', categorys => {
      this.categorys = categorys;
      this.loaded_categorys = true;

    });
  },
  methods: {
    addWork() {


      var params = {

        type: "addwork",
        iswork: true,
        newWork: {
          category: null,
          last_id: null,
          date_start: null,
          date_end: null,
        },
        modal_title: "Добавление работы",

        json: {
          "blocks": [{
            "type": "header",
            "data": {
              "text": "Название вашей работы",
              "level": 2
            }
          }]
        }
      };
      eventBus.$emit("call_modal_add_project", params)
      {


      }


    },
    cellclick(row, column, index) {
      if (column.customKey && column.customKey == "actions_delete") {
        Api.deleteUserPortfolio(row.last_id);

        this.data.splice(index, 1);
      } else if (column.customKey && column.customKey == "actions_edit") {
        var params = {

          type: "editwork",
          iswork: true,
          newWork: {
            category:  row.category.last_id,
            last_id: row.last_id,
            date_start: row.date_start,
            date_end:  row.date_end,
          },
          modal_title: "Редактирование работы",

          json:this.data[index].json
        };
        eventBus.$emit("call_modal_add_project", params)
        {


        }




      }
    },
    deleteWork(parameter, par2) {
      console.log('id delete');
      console.log(parameter);
      console.log(par2);
    },
    clickRow(row) {
      console.log('the row is');
      console.log(row);
    },
    onSubmit(event) {


    },
    invokeSave() {
      var app = this;

      this.$refs.editor._data.state.editor.save()
          .then((data) => {
            // Do what you want with the data here
            app.newWork.json = data;
            Api.addWork(app.newWork, function () {
              app.iserror = false;
              app.data = [];
              Api.loadUserPortfolio();
              app.config.blocks = null;
              app.modals.add = false;
              app.$refs.editor._data.state.editor.clear();
            }, function (message) {
              app.iserror = true;
              app.error_message = message;
            })

            console.log(data)
          })
          .catch(err => {
            console.log(err)
          })
    }
  },

  components: {Modal}
};
</script>
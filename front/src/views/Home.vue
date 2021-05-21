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

        <div class=" col-lg-8 justify-content-center">
          <card type="secondary" shadow
                header-classes="bg-white pb-5"
                body-classes=""
                class="border-0">

            <div class="col-md-12 center-block" v-if="is_load_projects==false">
              <sui-segment>
                <sui-dimmer active>
                  <sui-loader size="tiny"></sui-loader>
                </sui-dimmer>


              </sui-segment>
            </div>

            <div class="col-md-12 center-block" v-if="is_load_projects==true">

              <div class="row mt-5 mb-5">
                <div class=" col-md-5 mb-2 ">
                  <base-button class="ml-3" type="danger" @click="callModalAddProject">
                    Создать проект <i class="fa fa-plus-circle"></i>
                  </base-button>


                </div>
                <div class="col-md-5">
                  <select class="form-control" @change="changeStatusFilter" v-model="current_status">
                    <option :value="null">Показать все</option>
                    <option v-if="statuses.length>0" v-for="status in statuses" :value="status.last_id"
                            :key="status.last_id">{{
                        status.title
                      }}
                    </option>
                  </select>
                </div>
              </div>


              <div class="col-lg-12" v-if="projects.length>0">

                <div class="row mt-2"
                     v-if="(current_status==null || (current_status!=null && project.status==current_status)) && project_index>=(((projects_table.current_page*projects_table.on_page)-projects_table.on_page)-1) && project_index<=((projects_table.current_page*projects_table.on_page)-1)"
                     v-for="(project,project_index) in projects" :key="project.last_id">
                  <div class="col-lg-7 text-truncate">
                    <router-link class="text-clip" :to="{name: 'project', params: { id: project.last_id }}">
                      <base-button type="primary" class="text-clip" v-html="project.name_project">


                      </base-button>
                    </router-link>
                  </div>
                  <div class="col-lg-3">

                    <badge pill type="info" v-if="project.status_row.issearch==1">{{ project.status_row.title }}</badge>
                    <badge pill type="warning" v-if="project.status_row.iswork==1">{{ project.status_row.title }}
                    </badge>
                    <badge pill type="success" v-if="project.status_row.isfinish==1">{{ project.status_row.title }}
                    </badge>
                  </div>
                  <div class="col-lg-2">
                    <router-link :to="{name: 'project', params: { id: project.last_id }}"
                                 class="mb-3 mb-sm-0 btn btn-danger">
                      <i
                          class="ni ni-bold-right"></i>
                    </router-link>

                  </div>


                </div>


                <div class="row mt-2">
                  <base-pagination :per-page="projects_table.on_page" :total="projects_table.total"
                                   v-model="projects_table.current_page"></base-pagination>
                </div>


              </div>

            </div>
          </card>
        </div>
        <div class="col-lg-4">
          <card type="secondary" shadow
                header-classes="bg-white pb-5"
                body-classes=""
                class="border-0">
            <div class="row m-2">
              <h3 is="sui-header" dividing>Статистика</h3>
            </div>
            <div v-if="is_load_chart">
              <CircleChart :chartdata="circle_chart.chartdata" :options="circle_chart.options"/>
            </div>
          </card>
        </div>
      </div>
    </div>
  </section>

</template>
<script>

import {eventBus} from "@/main";
import axios from 'axios';
import Vue from "vue";
import Api from "../models/Api";
import Modal from "@/components/Modal";
import CircleChart from "@/models/CircleChart";


export default {
  name: "home",

  data() {

    var app = this;

    return {
      "is_load_projects": false,
      "projects_table": {
        "current_page": 1,
        "on_page": 10,
        "total": 0,
      },
      "statuses": {},
      "config": {
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
      },
      "current_status": null,
      "iserror": false,
      "error_message": null,
      "form": {
        'json': {},
      },
      "loaded_categorys": false,
      "categorys": null,
      "modal_title": "Создать проект",
      'modals': {
        "add": null,
      },
      "projects": [],
      "filter_projects": [],
      'is_load_chart': false,
      'circle_chart': {
        chartdata: {
          labels: ['Ищут исполнителя', 'В работе', 'Завершено',],
          datasets: [
            {
              label: 'Data One',
              backgroundColor: ['#03acca', '#ff3709', '#1aae6f'],
              data: []
            },

          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      }
    }
  },

  mounted: function () {

    var app = this;
    Api.loadStats(function (stats) {


      app.circle_chart.chartdata.datasets[0].data = [stats.freelance, stats.inwork, stats.finish];


      app.is_load_chart = true;
    })
    Api.loadProjects(function (data) {
      app.filter_projects = data.projects;
      app.statuses = data.statuses
      app.changeStatusFilter();
      app.projects_table.total = app.project.length;
    });


    Api.loadCategorys(function (data) {
      app.categorys = data;
      app.loaded_categorys = true;
    });


  },
  methods: {
    callModalAddProject() {
      eventBus.$emit("call_modal_add_project", {})
      {

      }
    },
    changeStatusFilter() {
      var app = this;
      var projects = [];
      if (Number.isInteger(app.current_status)) {
        app.projects_table.total = 0;
        var total = 0;
        app.filter_projects.forEach(function callback(currentValue, index, array) {
          //your iterator
          if (currentValue.status == app.current_status) {
            projects.push(currentValue);

          }
        });
        app.projects = projects;
      } else {
        app.projects = app.filter_projects;
      }

      app.projects_table.total = app.projects.length;
      app.is_load_projects = true;
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
                Api.loadProjects(function (data) {
                  app.projects = data.projects;
                  app.is_load_projects = true;
                });
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
      this.$refs.editor._data.state.editor.render({
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

  },
  components: {Modal, CircleChart}
};
</script>
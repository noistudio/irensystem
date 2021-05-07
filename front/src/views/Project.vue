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

          <div class="px-4">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#" v-if="project.client.avatar">
                    <img :src="image_url+project.client.avatar" class="rounded-circle">
                  </a>
                  <a href="#" v-if="project.client.avatar==null">
                    <avatar :username="project.client.name" size="150" class="rounded-circle"></avatar>
                  </a>
                </div>
              </div>
              <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
                <div class="card-profile-actions py-4 mt-lg-0">
                  <div v-if="(type=='developer' || type=='client' || type=='spectator') && project.invoices.length>0">
                    <base-button type="primary" @click="modals.modal_pj_invoices = true">
                      <span>Счета</span>
                      <badge type="white">{{ project.invoices.length }}</badge>
                    </base-button>
                    <modal modal-classes="modal-lg" :show.sync="modals.modal_pj_invoices">
                      <template slot="header">
                        <h5 class="modal-title" id="exampleModalLabel">Счета</h5>
                      </template>
                      <div class="" v-if="project.invoices.length>0">


                        <div class=" row mt-2" v-for="(project_invoice,index) in project.invoices"
                             :key="project_invoice.last_id">
                          <div class="col-md-2">@{{ users['last_id_' + project_invoice.client_id].name }} <i
                              class="fa fa-arrow-right"></i>
                            @{{ users['last_id_' + project_invoice.developer_id].name }}
                          </div>

                          <div class="col-md-3">
                            {{ project_invoice.sum }} {{ project_invoice.currency }}
                          </div>
                          <div class="col-md-3">
                            {{ project_invoice.title }}


                          </div>

                          <div class="col-md-2">


                            <badge pill v-if="project_invoice.final!=null && project_invoice.final.ispay!=1"
                                   type="warning">ожидает оплаты
                            </badge>
                            <badge pill
                                   v-if="project_invoice.is_approve_client==0 && me.last_id==project_invoice.developer_id"
                                   type="warning">не утвержден
                            </badge>
                            <badge pill
                                   v-if="project_invoice.is_approve_client==1 && me.last_id==project_invoice.developer_id"
                                   type="success"> утвержден
                            </badge>

                            <base-button type="primary"
                                         v-if="project_invoice.is_approve_client==0 && project_invoice.client_id==me.last_id"
                                         @click="approveInvoice($event,index)">Подтвердить
                            </base-button>
                            <base-button type="primary"
                                         v-if="project_invoice.is_approve_client==1 && project_invoice.is_finish==0 && project_invoice.client_id==me.last_id"
                                         @click="completeInvoice($event,index)">Все сделано
                            </base-button>

                            <base-button type="primary"
                                         v-if="project_invoice.is_approve_client==0 && project_invoice.developer_id==me.last_id"
                                         @click="removeInvoice($event,project_invoice.last_id)"><i
                                class="fa fa-remove"></i></base-button>
                          </div>
                        </div>
                      </div>
                      <template slot="footer">
                        <base-button type="secondary" @click="modals.modal_pj_invoices = false">Закрыть</base-button>

                      </template>
                    </modal>

                  </div>

                  <div class="h6 font-weight-300"
                       v-if="(project.developer_id==me.last_id ) && project.invoices.length>0">


                    <base-button class="mt-1" v-if="type=='developer'" type="primary" @click="modals.modal0 = true">
                      Добавить счет
                    </base-button>
                    <modal :show.sync="modals.modal0" v-if="type=='developer'">
                      <template slot="header">
                        <h5 class="modal-title" id="exampleModalLabel2">Добавить счет</h5>
                      </template>
                      <div>
                        <div class="alert alert-danger" v-if="new_invoice_iserror==true">
                          <p>{{ new_invoice_error }}</p>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-5">
                            <label for="exampleUser">Пользователь </label>
                            <select id="exampleUser" v-if="is_load==true" required v-model="newInvoice.user_id"
                                    class="form-control">
                              <option v-if="user.last_id!=me.last_id || (user.role=='spectator' && user.isapprove==0)"
                                      v-for="user in users" :value="user.last_id"
                                      :key="user.last_id">@{{
                                  user.name
                                }}
                              </option>

                            </select>


                          </div>
                          <div class="form-group col-md-5">
                            <label for="exampleTypeInvoice">Тип </label>
                            <select id="exampleTypeInvoice" v-if="is_load==true" required
                                    class="form-control" v-model="newInvoice.type">
                              <option value="client" v-if="newInvoice.user_id==project.client_id">
                                должен
                                оплатить
                              </option>
                              <option value="developer" v-if="newInvoice.user_id!=project.client_id">
                                должен получить
                              </option>
                            </select>


                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-5">
                            <label for="exampleSum">Сумма </label>
                            <input type="text" class="form-control" required id="exampleSum" v-model="newInvoice.sum"
                                   placeholder="Введите сумму">

                          </div>
                          <div class="form-group col-md-5">
                            <label for="exampleCurrency5">Валюта </label>
                            <select id="exampleCurrency5" v-if="is_load==true" required
                                    class="form-control" v-model="newInvoice.currency">
                              <option value="RUB">RUB</option>
                              <option value="USD">USD</option>
                              <option value="EURO">EURO</option>
                            </select>


                          </div>
                        </div>
                        <div class="row">
                          <label for="exampleTitle5">Описание услуги </label>
                          <input type="text" class="form-control" required id="exampleTitle5" v-model="newInvoice.title"
                                 placeholder="Введите описание">
                        </div>
                      </div>
                      <template slot="footer">
                        <base-button type="secondary" @click="modals.modal0 = false">Закрыть</base-button>
                        <base-button type="primary" @click="addInvoice()">Создать счет</base-button>
                      </template>
                    </modal>
                  </div>
                  <div class="h6 font-weight-300 mt-2">
                    <h3 v-if="type=='spectator'">{{ project.status_row.title }}</h3>
                    <select class="form-control" @change="setNewStatus" v-model="project.status"
                            v-if="project.statuses.length>0 && type!='spectator'">
                      <option v-for="status in project.statuses"
                              :value="status.last_id" :key="status.last_id">
                        {{ status.title }}

                      </option>

                    </select>

                  </div>

                </div>
              </div>
              <div class="col-lg-4 order-lg-1">
                <div class="h6 font-weight-300">
                  <h3 v-html="project.name_project"></h3>
                  <h3><i class="fa fa-calendar"></i> {{ project.start_time }} </h3>

                </div>
                <div class="h6 font-weight-300"
                     v-if="project.status_row.issearch==0 && project.main_project_id==0 && type!='spectator' ">
                  <button class="btn btn-warning" @click="addTaskClick">Добавить задачу</button>

                </div>

                <div class="h6 font-weight-300" v-if="project.status_row.issearch==0 && type!='spectator'">
                  <div>
                    <base-button type="primary" @click="modals.users = true">
                      <i class="fa fa-users"></i> Участники
                    </base-button>
                    <modal :show.sync="modals.users">
                      <template slot="header">
                        <h5 class="modal-title">Список участников</h5>
                      </template>
                      <div>

                        <sui-card-group :items-per-row="2">
                          <sui-card v-for="user_project in users" :key="user_project.last_id">
                            <sui-card-content>
                              <avatar v-if="user_project.avatar==null" :size="187" :username="user_project.name"
                                      class="right floated ui image"></avatar>
                              <sui-image v-if="user_project.avatar"
                                         :src="image_url+user_project.avatar"
                                         class="right floated"
                              />
                              <sui-card-header>{{ user_project.name }}</sui-card-header>
                              <sui-card-meta v-if="user_project.role=='client'">Клиент</sui-card-meta>
                              <sui-card-meta v-if="user_project.role=='developer'">Исполнитель
                              </sui-card-meta>
                              <sui-card-meta
                                  v-if="user_project.role=='spectator'">
                                Наблюдатель
                              </sui-card-meta>
                              <sui-card-description v-if="user_project.role=='spectator' && user_project.isapprove==0">
                                Еще не принял приглашение!
                              </sui-card-description>
                            </sui-card-content>
                            <sui-card-content extra>
                              <sui-container text-align="center">
                                <sui-button-group v-if="user_project.role=='spectator'">

                                  <sui-button @click="deleteSpectator(user_project.last_id)" basic negative>Удалить
                                  </sui-button>
                                </sui-button-group>
                              </sui-container>
                            </sui-card-content>
                          </sui-card>

                        </sui-card-group>

                        <div class="mt-5 alert alert-danger" v-if="invite_user_is_error==true">
                          <p>{{ invite_user_message }}</p>
                        </div>
                        <div class="row mt-5">
                          <div class="form-group col-md-6">
                            <label for="exampleUsername">Юзернейм наблюдателя </label>
                            <input type="text" class="form-control" required id="exampleUsername" v-model="invite_user"
                                   placeholder="Введите username">

                          </div>
                          <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-success" @click="inviteUser()">Пригласить участника</button>
                          </div>
                        </div>

                      </div>
                      <template slot="footer">
                        <base-button type="secondary" @click="modals.users = false">Закрыть</base-button>

                      </template>
                    </modal>

                  </div>
                </div>

              </div>
            </div>
            <div class="text-center mt-5">


            </div>
            <div class="mt-5 py-5 border-top text-center">

            </div>
          </div>
          <div class="container container-lg">

            <div class="col-md-10 mt-2 mb-2">


              <sui-breadcrumb v-if="project.main_project_id>0 && project.main_project.last_id">
                <sui-breadcrumb-section @click="openTask(project.main_project.last_id)" link
                                        v-html="project.main_project.name_project"></sui-breadcrumb-section>
                <sui-breadcrumb-divider icon="right chevron"/>

                <sui-breadcrumb-section active
                >{{ project.name_project }}
                </sui-breadcrumb-section
                >
              </sui-breadcrumb>


              <sui-step-group size="mini" v-if="project.main_project_id==0">
                <a is="sui-step" :active="project.status_row.issearch==1" icon="shopping cart">
                  <sui-step-content
                      title="Поиск исполнителя"
                      description="Выберите исполнителя который вам понравился :)"

                  />
                </a>

                <a is="sui-step" :active="project.status_row.iswork==1" icon="handshake outline">
                  <sui-step-content
                      title="Проект в работе"
                      description="Плодотворная работа в самом разгаре"
                  />
                </a>
                <a is="sui-step" :active="project.status_row.isfinish==1" icon="thumbs up outline">
                  <sui-step-content
                      title="Проект завершен"
                      description="Проект завершен, радуйтесь и пляшите"
                  />
                </a>
              </sui-step-group>
            </div>

            <div class="col-md-10 center-block  mt-5">


              <editorjsrender :blocks_json="project.json.blocks"></editorjsrender>

              <div class="row ml-2 mt-5"
                   v-if="project.client_id==me.last_id || (project.status_row.issearch==0 && type=='developer')">
                <button class="btn btn-warning" @click="editProjectClick">Редактировать проект</button>
              </div>
              <div class="row  ml-2 mt-5" v-if="project.tasks.length>0">
                <h4>Задачи:</h4>


                <sui-item-group divided>
                  <sui-item v-for="(task,task_index) in project.tasks" :key="task.last_id"
                            v-if="task_index>=(((tasks_table.current_page*tasks_table.on_page)-tasks_table.on_page)-1) && task_index<=((tasks_table.current_page*tasks_table.on_page)-1)">
                    <sui-item-image v-if="task.client.avatar" :src="image_url+task.client.avatar" size="tiny"/>

                    <div class="ui tiny image" v-if="task.client.avatar==null">

                      <avatar v-if="task.client.avatar==null" :size="60" :username="task.client.name"
                              class="rounded-circle"></avatar>

                    </div>

                    <sui-item-content vertical-align="middle">
                      <button @click="openTask(task.last_id)"
                              class="mb-3 text-clip mb-sm-0 btn btn-danger">


                        {{ task.name_project }}


                      </button>
                      <sui-button basic positive>{{ task.status_row.title }}</sui-button>


                    </sui-item-content>
                  </sui-item>


                </sui-item-group>


              </div>
              <div class="row mt-5" v-if="project.tasks.length>0">

                <base-pagination :per-page="tasks_table.on_page" :total="project.tasks.length"
                                 v-model="tasks_table.current_page"></base-pagination>
              </div>

            </div>

            <div class="col-md-10 center-block mt-5" v-if="project.status_row.issearch==1">
              <sui-comment-group size="massive" threaded>
                <h3 is="sui-header" dividing>Заявки от исполнителей</h3>

                <sui-comment v-for="(offer,offer_index) in project.offers" :key="offer.last_id">
                  <sui-comment-avatar v-if="offer.developer.avatar" :src="image_url+offer.developer.avatar"/>
                  <div class="avatar" v-if="offer.developer.avatar==null" :src="image_url+offer.developer.avatar">

                    <avatar v-if="offer.developer.avatar==null" :size="35" :username="offer.developer.name"
                            class="rounded-circle"></avatar>

                  </div>


                  <sui-comment-content>
                    <a is="sui-comment-author">{{ offer.developer.name }}</a>
                    <sui-comment-metadata>
                      <div>{{ offer.created_at }}</div>
                      <div> {{ offer.price }} <strong><i class="fa fa-euro" v-if="offer.currency=='EURO'"></i> <i
                          class="fa fa-ruble"
                          v-if="offer.currency=='RUB'"></i>
                        <i class="fa fa-usd" v-if="offer.currency=='USD'"></i></strong>

                      </div>
                      <div>сроки <i class="fa fa-calendar-times-o"></i>{{ offer.date_end }}</div>
                      <div class="" v-if="me.last_id==project.client_id">
                        <button class="btn btn-primary" @click="ChooseOffer(offer.last_id)">Выбрать исполнителя</button>
                      </div>
                    </sui-comment-metadata>
                    <sui-comment-text>{{ offer.comment }}</sui-comment-text>

                  </sui-comment-content>
                  <sui-comment-group v-if="me.last_id==offer.developer_id || type=='client'">

                    <sui-comment v-for="(offer_comment,index_offer_comment) in offer.comments"
                                 :key="offer_comment.last_id">
                      <sui-comment-avatar v-if="offer_comment.user.avatar" :src="image_url+offer_comment.user.avatar"/>
                      <div class="avatar" v-if="offer_comment.user.avatar==null"
                           :src="image_url+offer_comment.user.avatar">

                        <avatar v-if="offer_comment.user.avatar==null" :size="35" :username="offer_comment.user.name"
                                class="rounded-circle"></avatar>

                      </div>
                      <sui-comment-content>
                        <a is="sui-comment-author">{{ offer_comment.user.name }}</a>
                        <sui-comment-metadata>
                          <div>{{ offer_comment.created_at }}</div>
                        </sui-comment-metadata>
                        <sui-comment-text>
                          {{ offer_comment.comment }}
                        </sui-comment-text>

                      </sui-comment-content>
                    </sui-comment>
                    <sui-comment>
                      <div class="row">
                        <div class="col-md-8">
                          <textarea class="row form-control" v-model="subcomment['offer'+offer.last_id]"></textarea>
                        </div>
                        <div class="col-md-4">
                          <button class="btn btn-success" @click="sendOfferComment(offer.last_id,offer_index)"><i
                              class="fa fa-arrow-circle-right"></i></button>
                        </div>
                      </div>
                    </sui-comment>
                  </sui-comment-group>
                </sui-comment>


                <h3 is="sui-header" dividing v-if="me.last_id!=project.client_id">Оставить заявку</h3>
                <div class="alert alert-danger" v-if="new_offer_is_error==true">
                  <p>{{ new_offer_error }}</p>
                </div>
                <div class="row " v-if="me.last_id!=project.client_id">
                  <div class="form-group col-md-4">
                    <label for="exampleSum">Сумма </label>
                    <input type="text" class="form-control" required id="exampleSum" v-model="newOffer.price"
                           placeholder="Введите сумму">

                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleCurrency">Валюта </label>
                    <select id="exampleCurrency" v-if="is_load==true" required
                            class="form-control" v-model="newOffer.currency">
                      <option value="RUB">RUB</option>
                      <option value="USD">USD</option>
                      <option value="EURO">EURO</option>
                    </select>


                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleDate">Срок до </label>
                    <input type="date" class="form-control" required id="exampleDate" v-model="newOffer.date_end"
                           placeholder="Укажите дату">

                  </div>
                </div>

                <div class="row pt-2 pb-2" v-if="me.last_id!=project.client_id">
                  <div class="col-md-8">
                    <label for="exampleTitle">Описание услуги </label>
                    <input type="text" class="form-control" required id="exampleTitle" v-model="newOffer.comment"
                           placeholder="Введите описание">
                  </div>
                  <div class="col-md-4">
                    <label for="exampleTitle">Заполните все поля :)</label>
                    <button class=" btn btn-success" @click="sendOffer">{{ offer_btn_edit }}</button>
                  </div>
                </div>


              </sui-comment-group>
            </div>


            <div class="col-md-10 center-block mt-5" v-if="project.status_row.iswork==1">
              <sui-comment-group size="massive" threaded>
                <h3 is="sui-header" dividing>Комментарии</h3>

                <sui-comment v-for="(comment,comment_index) in project.comments" :key="comment.last_id">
                  <sui-comment-avatar v-if="comment.user.avatar" :src="image_url+comment.user.avatar"/>
                  <div class="avatar" v-if="comment.user.avatar==null">

                    <avatar v-if="comment.user.avatar==null" :size="35" :username="comment.user.name"
                            class="rounded-circle"></avatar>

                  </div>


                  <sui-comment-content>
                    <a is="sui-comment-author">{{ comment.user.name }}</a>
                    <sui-comment-metadata>
                      <div>{{ comment.created_at }}</div>


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
                        </sui-comment-metadata>
                        <sui-comment-text>
                          {{ subcomment.comment }}
                        </sui-comment-text>

                      </sui-comment-content>
                    </sui-comment>
                    <sui-comment>
                      <div class="row">
                        <div class="col-md-8">
                          <textarea class="row form-control"
                                    v-model="project_subcomment['comment'+comment.last_id]"></textarea>
                        </div>
                        <div class="col-md-4">
                          <button class="btn btn-success" @click="sendSubComment(comment.last_id,comment_index)"><i
                              class="fa fa-arrow-circle-right"></i></button>
                        </div>
                      </div>
                    </sui-comment>
                  </sui-comment-group>
                </sui-comment>


                <h3 is="sui-header" dividing>Написать комментарий</h3>
                <div class="alert alert-danger" v-if="new_comment_is_error==true">
                  <p>{{ new_comment_error }}</p>
                </div>


                <div class="row pt-2 pb-2">
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

    <modal modal-classes="modal-lg" :show.sync="modals.add">
      <template slot="header">
        <h5 class="modal-title" id="exampleModalLabel">{{ modal_title }}</h5>
      </template>
      <div>
        <div class="alert alert-danger" v-if="iserror==true">
          <p>{{ error_message }}</p>
        </div>

        <div class="form-group row" v-if="type=='developer' && is_add_task">
          <label for="exampleUser">Исполнитель задачи</label>
          <select id="exampleUser" v-if="is_load==true" required v-model="new_task_developer"
                  class="form-control">
            <option value="me">Я</option>

            <option v-if="user.last_id!=me.last_id && user.isapprove==1 && user.role=='spectator'" v-for="user in users"
                    :value="user.last_id"
                    :key="user.last_id">@{{
                user.name
              }}
            </option>

          </select>


        </div>


      </div>
      <template slot="footer">
        <base-button type="secondary" @click="modals.add = false">Close</base-button>
        <base-button @click="doEditProject" type="primary">Сохранить</base-button>
      </template>
    </modal>


  </div>
</template>
<script>

import {eventBus, vm} from "@/main";
import wysiwyg from "vue-wysiwyg";
import Modal from "@/components/Modal";
import axios from "axios";
import Vue from "vue";
import Api from "../models/Api";
import Editorjsrender from "../models/Editorjsrender.vue";


var Avatar = require('vue-avatar')


export default {
  data() {
    var app = this;
    return {
      "tasks_table": {
        "current_page": 1,
        "on_page": 5
      },
      "invite_user_is_error": false,
      "invite_user_message": null,
      "invite_user": null,
      'modals': {
        'modal0': null,
        'modal_pj_invoices': null,
        'add': null,
        "users": null,
      },

      "project_subcomment": {},
      "subcomment": {},
      "modal_title": "Добавить работу",
      "editProject": {

        'json': null,

      },
      "new_offer_is_error": false,
      "new_offer_error": null,
      "new_comment_is_error": false,
      "new_comment_error": null,
      "newComment": {
        "comment": null
      },

      "newOffer": {
        "last_id": null,
        "price": null,
        "currency": null,
        "date_end": null,
        "comment": null,
      },
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
          attaches: {
            class: require('@editorjs/attaches'),
            config: {
              endpoint: Vue.config.API_URL + 'editor/uploadFile?api_token=' + app.$store.getters.TOKEN,
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

      'is_add_task': false,
      'new_invoice_iserror': false,
      'new_invoice_error': null,
      'project_invoices': {},
      'newInvoice': {},
      'type': 'client',
      'image_url': null,
      'iserror': false,
      'error_message': null,
      'users': null,
      'isclose': false,
      'project': {},
      'is_load': false,
      "offer_btn_edit": "Отправить предложение",

      'me': null,
      "new_task_developer": null,

      "comments": []
    }
  },
  mounted() {
    var app = this;
    let id = app.$route.params.id;
    Api.loadProject(id);


    eventBus.$on("update_project_tasks", data => {
      app.project.tasks = data.tasks;

    })
    eventBus.$on("update_project_info", data => {
      app.project.json = data.json;

    })
    eventBus.$on('is_load_project', data => {
      app.project = data.project;

      app.type = data.role;
      app.is_load = true;
      app.users = data.users;
      app.image_url = Vue.config.IMAGE_URL;
      app.me = app.$store.getters.USER;
      if (app.project.edit_offer && app.project.edit_offer.last_id) {
        app.newOffer.comment = app.project.edit_offer.comment;
        app.newOffer.price = app.project.edit_offer.price;
        app.newOffer.currency = app.project.edit_offer.currency;
        app.newOffer.date_end = app.project.edit_offer.date_end;
        app.newOffer.last_id = app.project.edit_offer.last_id;
        app.offer_btn_edit = "Редактировать";
      }
      // if (data.project_invoices == null) {
      //   app.project_invoices = {};
      // } else {
      //   app.project_invoices = data.project_invoices;
      // }
      // app.newComment.project_id = app.project.last_id;
      // if (app.project.isclose == 0) {
      //   app.isclose = false;
      // } else {
      //   app.isclose = true;
      // }


    });
  },
  methods: {

    completeInvoice(event, index) {
      event.preventDefault();
      var app = this;
      var id = app.project.invoices[index].last_id;
      var project_id = app.project.last_id;
      Api.completeInvoice(id, project_id, function (data) {

        app.project.invoices = data.project.invoices;
      });
    },
    approveInvoice(event, index) {
      event.preventDefault();
      var app = this;
      var id = app.project.invoices[index].last_id;
      var project_id = app.project.last_id;
      Api.approveInvoice(id, project_id, function (data) {
        app.project.invoices = data.project.invoices;
      });
    },
    deleteSpectator(user_id) {
      var app = this;
      Api.deleteSpectator(app.project.last_id, user_id, function (users) {


        app.users = users;
      }, function (message) {

      });
    },
    inviteUser() {
      var app = this;
      Api.addUser(app.project.last_id, app.invite_user, function (users) {

        app.invite_user_is_error = false;
        app.invite_user = null;
        app.users = users;
      }, function (message) {
        app.invite_user_is_error = true;
        app.invite_user_message = message;
      });
    },
    removeInvoice(event, id) {
      event.preventDefault();
      console.log("remove invoice");
      console.log(id);
      var app = this;
      var project_id = app.project.last_id;
      Api.removeInvoice(id, project_id, function (data) {
        console.log('remove invoice');

        app.project.invoices = data.project.invoices;
        // for (var i = app.project.invoices.length - 1; i >= 0; --i) {
        //   if (app.project.invoices[i].last_id == id) {
        //     app.project.invoices = app.project.invoices.splice(i, 1);
        //   }
        // }
        // document.location.reload();
      });

    },
    addTaskClick() {
      var params = {
        "project_id": this.project.last_id,
        type: "addtask",
        type_user: this.type,
        modal_title: "Добавить задачу",
        users: this.users,
        json: {
          "blocks": [{
            "type": "header",
            "data": {
              "text": "Название задачи вашей мечты!",
              "level": 2
            }
          }]
        }
      };

      eventBus.$emit("call_modal_add_project", params)
      {


      }


    },
    editProjectClick() {
      var params = {
        "project_id": this.project.last_id,
        type: "edit",
        modal_title: "Редактирование проекта",
        json: this.project.json
      };
      eventBus.$emit("call_modal_add_project", params)
      {


      }
      // this.$refs.editor._data.state.editor.render(this.project.json);
      // app.is_add_task = false;
      // this.modal_title = "Редактирование проекта";
      // this.modals.add = true;
    },
    ChooseOffer(offer_id) {
      var app = this;
      Api.chooseOfferProject(app.project.last_id, offer_id, function (project) {
        app.$router.replace({"name": "project", "params": {"id": project.last_id, "rand": Math.random()}});
        // this.$router.push({"name": "project", "params": {"id": button.params.id, "rand": Math.random()}});
        vm.$forceUpdate();
      });
    },
    addInvoice() {
      var app = this;
      var project_id = app.project.last_id;
      var newInvoice = app.newInvoice;
      Api.addProjectInvoice(project_id, newInvoice, function (data) {
        app.new_invoice_iserror = false;

        app.newInvoice = {};
        app.project = data.project;
        //eventBus.$emit('is_load_project', data);
        app.modals.modal0 = false;
      }, function (message) {
        app.new_invoice_iserror = true;
        app.new_invoice_error = message;
      })


    },
    doEditProject() {
      var app = this;

      this.$refs.editor._data.state.editor.save()
          .then((data) => {
            // Do what you want with the data here
            var edit_project = {"last_id": app.project.last_id, "json": data};

            if (app.is_add_task == true) {
              edit_project.developer = app.new_task_developer;
              Api.addTask(edit_project, function (tasks) {
                app.iserror = false;

                app.modals.add = false;
                app.is_add_task = false;
                app.project.tasks = tasks;

                app.$refs.editor._data.state.editor.clear();

              }, function (message) {
                app.iserror = true;
                app.error_message = message;
              })
            } else {
              Api.editProject(edit_project, function () {
                app.iserror = false;

                app.modals.add = false;
                app.project.json = edit_project.json;
                app.$refs.editor._data.state.editor.clear();

              }, function (message) {
                app.iserror = true;
                app.error_message = message;
              })
            }

            console.log(data)
          })
          .catch(err => {
            console.log(err)
          })
    },
    sendOfferComment(offer_id, index) {
      var app = this;
      console.log('send comment');
      var comment = app.subcomment['offer' + offer_id];
      Api.sendOfferComment(app.project.last_id, offer_id, comment, function (comments) {
        app.project.offers[index].comments = comments;
        app.subcomment['offer' + offer_id] = "";
      });

    },

    sendOffer() {
      var app = this;
      app.newOffer.project_id = this.project.last_id;
      Api.sendOffer(app.newOffer, function (offer) {
        app.new_invoice_iserror = false;
        if (app.newOffer.last_id == null) {
          app.project.offers = app.project.offers.concat([offer]);
          app.newOffer.last_id = offer.last_id;
        } else {


          const index = app.project.offers.findIndex(item => {
            return (offer.last_id === item.last_id)
          })
          app.project.offers.splice(index, 1, offer)

        }


        app.offer_btn_edit = "Отредактировать";

      }, function (message) {
        app.new_invoice_iserror = true;
        app.new_invoice_error = message;
      })
    },
    sendSubComment(comment_id, comment_index) {
      var app = this;
      console.log('send comment');
      var comment = app.project_subcomment['comment' + comment_id];
      Api.sendSubComment(app.project.last_id, comment_id, comment, function (comments) {
        app.project.comments[comment_index].comments = comments;
        app.project_subcomment['comment' + comment_id] = "";
      });
    },
    openTask(task_id) {

      window.location.hash = "#/project/" + task_id;
      this.$route.params.id = task_id;
      Api.loadProject(task_id);
    },
    setNewStatus() {
      var app = this;
      var status = app.project.status;
      Api.setNewStatus(status, app.project.last_id, function () {

      });
    },
    sendComment(event) {
      event.preventDefault();
      var app = this;
      var newComment = app.newComment;
      Api.addComment(newComment, app.project.last_id, function (result_comment) {
        app.iserror = false;

        app.newComment.comment = "";
        app.project.comments.push(result_comment)
      }, function (message) {
        app.iserror = true;
        app.error_message = message;
      })


    },
  },
  components: {

    Modal, 'editorjsrender': Editorjsrender,
    'avatar': Avatar.Avatar
  }
};
</script>
<style>
</style>

<card shadow class="card-profile mt--300" no-body>
<div class="px-4">
  <div class="row justify-content-center">
    <div class="col-lg-3 order-lg-2">
      <div class="card-profile-image">
        <a href="#">
          <img v-lazy="'img/theme/team-4-800x800.jpg'" class="rounded-circle">
        </a>
      </div>
    </div>
    <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
      <div class="card-profile-actions py-4 mt-lg-0" v-if="type=='client'">
        <div v-if="project_invoices.length>0">
          <base-button type="primary" @click="modals.modal_pj_invoices = true">
            <span>Счета</span>
            <badge type="white">{{ project_invoices.length }}</badge>
          </base-button>
          <modal :show.sync="modals.modal_pj_invoices">
            <template slot="header">
              <h5 class="modal-title" id="exampleModalLabel">Счета</h5>
            </template>
            <div class="" v-if="project_invoices.length>0">

              <div class=" row mt-2" v-for="(project_invoice,index) in project_invoices"
                   :key="project_invoice.last_id">

                <div class="col-md-4">
                  {{ project_invoice.sum }} {{ project_invoice.currency }}
                </div>
                <div class="col-md-4">
                  {{ project_invoice.title }}


                </div>

                <div class="col-md-4">
                  <badge pill v-if="project_invoice.final!=null" type="warning">ожидает оплаты</badge>
                  <base-button type="primary" v-if="project_invoice.is_approve_client==0"
                               @click="approveInvoice($event,index)">Подтвердить
                  </base-button>
                  <base-button type="primary"
                               v-if="project_invoice.is_approve_client==1 && project_invoice.is_finish==0"
                               @click="completeInvoice($event,index)">Все сделано
                  </base-button>
                </div>
              </div>
            </div>
            <template slot="footer">
              <base-button type="secondary" @click="modals.modal_pj_invoices = false">Закрыть</base-button>

            </template>
          </modal>

        </div>


      </div>

      <div class="card-profile-actions py-5 mt-lg-0" v-if="type=='developer'">

        <div v-if="project_invoices.length>0">
          <base-button type="primary" @click="modals.modal_pj_invoices = true">
            <span>Счета</span>
            <badge type="white">{{ project_invoices.length }}</badge>
          </base-button>
          <modal :show.sync="modals.modal_pj_invoices">
            <template slot="header">
              <h5 class="modal-title" id="exampleModalLabel">Счета</h5>
            </template>
            <div class="" v-if="project_invoices.length>0">

              <div class=" row mt-2" v-for="project_invoice in project_invoices"
                   :key="project_invoice.last_id">
                <div class="col-md-3">
                  {{ project_invoice.sum }} {{ project_invoice.currency }}
                </div>
                <div class="col-md-3">
                  {{ project_invoice.title }}


                </div>
                <div class="col-md-3">
                  <badge pill v-if="project_invoice.final!=null" type="warning">ожидает оплаты</badge>
                  <badge pill v-if="project_invoice.is_approve_client==0" type="warning">не утвержден</badge>
                  <badge pill v-if="project_invoice.is_approve_client==1" type="success"> утвержден</badge>
                </div>
                <div class="col-md-2">
                  <base-button type="primary" v-if="project_invoice.is_approve_client==0"
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


        <base-button class="mt-1" type="primary" @click="modals.modal0 = true">
          Добавить счет
        </base-button>
        <modal :show.sync="modals.modal0">
          <template slot="header">
            <h5 class="modal-title" id="exampleModalLabel">Добавить счет</h5>
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
                  <option v-for="user in users" :value="user.last_id" :key="user.last_id">@{{ user.name }}
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
                <label for="exampleCurrency">Валюта </label>
                <select id="exampleCurrency" v-if="is_load==true" required
                        class="form-control" v-model="newInvoice.currency">
                  <option value="RUB">RUB</option>
                  <option value="USD">USD</option>
                  <option value="EURO">EURO</option>
                </select>


              </div>
            </div>
            <div class="row">
              <label for="exampleTitle">Описание услуги </label>
              <input type="text" class="form-control" required id="exampleTitle" v-model="newInvoice.title"
                     placeholder="Введите описание">
            </div>
          </div>
          <template slot="footer">
            <base-button type="secondary" @click="modals.modal0 = false">Закрыть</base-button>
            <base-button type="primary" @click="addInvoice()">Создать счет</base-button>
          </template>
        </modal>


      </div>
    </div>
    <div class="col-lg-4 order-lg-1">
      <div class="card-profile-stats d-flex justify-content-center">
        <h4>{{ project.name_project }}</h4>
        <a class="btn btn-danger" :href="project.url_task" target="_blank"><i
            class="ni ni-cloud-download-95"></i></a>
      </div>

    </div>
  </div>


</div>
<div class="container container-lg">

  <div class="col-md-12">
    <editorjsrender :blocks_json="project.json.blocks"></editorjsrender>
  </div>
  <div class="row">
    <sui-step-group>
      <a is="sui-step" active icon="truck">
        <sui-step-content
            title="Поиск исполнителя"
            description="Выберите исполнителя который вам понравился :)"
        />
      </a>

      <a is="sui-step">
        <sui-step-content
            title="Проект в работе"
            description="Плодотворная работа в самом разгаре"
        />
      </a>
      <a is="sui-step">
        <sui-step-content
            title="Проект завершен"
            description="Проект завершен, радуйтесь и пляшите"
        />
      </a>
    </sui-step-group>
  </div>
</div>

</card>
<template>
  <div id="app">
    <router-view name="header"></router-view>
    <main>
      <fade-transition origin="center" mode="out-in" :duration="250">
        <router-view :key="$route.fullPath+$route.params.toString()"/>
      </fade-transition>
    </main>
    <router-view name="footer"></router-view>
  </div>
</template>
<script>
import {FadeTransition} from "vue2-transitions";
import {eventBus} from "./main.js";
import axios from "axios";
import Vue from "vue";
import Api from "./models/Api";


export default {
  components: {
    FadeTransition
  },
  mounted() {
    let app = this;
    if (app.$store.getters.TOKEN != undefined) {
      Api.loadUserInfo(app.$store.getters.TOKEN);
    }
    eventBus.$on('reload', (user) => {

      Api.loadUserInfo(user.api_token);
    });


    eventBus.$on('update_user_info', (isauth, isadmin) => { // here you need to use the arrow function
      app.isauth = isauth;
      app.isadmin = isadmin;

    })
  },
};
</script>

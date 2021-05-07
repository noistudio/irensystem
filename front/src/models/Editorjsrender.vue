<template>
  <div>

    <div class="" v-for="(block,index) in blocks_json" :key="index">
      <p class="lead" v-if="block.type=='paragraph'" v-html="block.data.text"></p>
      <h1 class="mt-1" v-if="block.type=='header' && block.data.level==1" v-html="block.data.text"></h1>
      <h2 class="mt-1" v-if="block.type=='header' && block.data.level==2" v-html="block.data.text"></h2>
      <h3 class="mt-1" v-if="block.type=='header' && block.data.level==3" v-html="block.data.text"></h3>
      <h4 class="mt-1" v-if="block.type=='header' && block.data.level==4" v-html="block.data.text"></h4>
      <h5 class="mt-1" v-if="block.type=='header' && block.data.level==5" v-html="block.data.text"></h5>
      <h6 class="mt-1" v-if="block.type=='header' && block.data.level==6" v-html="block.data.text"></h6>
      <ol class="mt-1" v-if="block.type=='list' && block.data.style=='ordered' && block.data.items.length>0">
        <li v-for="(item_ordered,index) in block.data.items" :key="index" v-html="item_ordered"></li>

      </ol>
      <ul class="mt-1" v-if="block.type=='list' && block.data.style=='unordered' && block.data.items.length>0">
        <li v-for="(item_unordered,index) in block.data.items" :key="index" v-html="item_unordered"></li>

      </ul>
      <div class="row mt-1" v-if="block.type=='image'">
        <img :src="block.data.file.url" class="col-md-10 img img-center">


      </div>
      <p class="text-center" v-if="block.type=='image' && block.data.caption">
        {{ block.data.caption }}</p>

      <div class="col-md-10 mt-1 ml-1" v-if="block.type=='attaches'">
        <p><a class="btn btn-primary col-md-7 text-clip" :href="block.data.file.url" target="_blank"><i
            class="fa fa-download"></i> Скачать
          {{ block.data.file.name }}</a></p>
      </div>


      <iframe v-if="block.type=='embed'" :src="block.data.embed" :width="block.data.width"
              :height="block.data.height"></iframe>
      <blockquote v-if="block.type=='quote' && block.data.alignment=='left'" class="blockquote text-left">
        <p class="mb-0">{{ block.data.text }}</p>
        <footer v-if="block.data.caption.length>0" class="blockquote-footer">{{ block.data.caption }}

        </footer>
      </blockquote>
      <blockquote v-if="block.type=='quote' && block.data.alignment=='center'" class="blockquote text-center">
        <p class="mb-0">{{ block.data.text }}</p>
        <footer v-if="block.data.caption.length>0" class="blockquote-footer">{{ block.data.caption }}

        </footer>
      </blockquote>
      <p class="lead" v-if="block.type=='code'"><code>{{ block.data.code }}</code></p>
      <a :href="block.data.link" v-if="block.type=='linkTool'">
        <div class="media" v-if="block.type=='linkTool'">
          <img v-if="block.data.meta && block.data.meta.image && block.data.meta.image.url"
               :src="block.data.meta.image.url" class="mr-3" alt="...">
          <div class="media-body">
            <h5 class="mt-0" v-if="block.data.meta && block.data.meta.title">{{ block.data.meta.title }}</h5>
            <p class="lead" v-if="block.data.meta && block.data.meta.site_name">
              {{ block.data.meta.site_name }}</p>
            <p class="lead" v-if="block.data.meta && block.data.meta.description">
              {{ block.data.meta.description }}</p>
            <p class="lead" v-if="block.data.link">{{ block.data.link }}</p>
          </div>
        </div>
      </a>
      <p class="lead" v-if="block.type=='delimiter'">* * *</p>
      <div v-if="block.type=='raw'" v-html="block.data.html">
      </div>
      <table class="table mt-2" v-if="block.type=='table'">
        <tr v-for="(tr,index) in block.data.content" :key="index">
          <td v-for="(td,index) in tr" :key="index">{{ td }}</td>
        </tr>
      </table>

      <div class="alert alert-warning" v-if="block.type=='warning'" role="alert">
        <h4 class="alert-heading" v-if="block.data.title">{{ block.data.title }}</h4>
        <p v-if="block.data.message">{{ block.data.message }}</p>
      </div>

      <ul v-if="block.type=='checklist'  && block.data.items.length>0">
        <li v-for="(item_check,index) in block.data.items" :key="index">
          <b v-if="item_check.checked==false">{{ item_check.text }}</b>
          <s v-if="item_check.checked==true">{{ item_check.text }}</s>
        </li>

      </ul>


    </div>
  </div>
</template>
<script>
import Vue from "vue";

export default {

  props: {
    blocks_json: {
      type: Array,
      required: true
    },

  },

  data: function () {
    return {}
  },
}

</script>
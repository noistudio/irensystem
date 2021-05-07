<template>
  <footer class="footer has-cards">

    <div class="container">
      <div class="row row-grid align-items-center my-md">
        <div class="col-lg-12">
          <h3 class="text-primary font-weight-light mb-2">Artemdev.ru</h3>
          <h4 class="mb-0 font-weight-light">Профессиональная разработка проектов высокой сложности</h4>
        </div>

      </div>
      <hr>
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-4">
          <div class="copyright">
            &copy; {{ year }}
            Artemdev.ru
          </div>
        </div>
        <div class="col-md-8">
          <p>UI & Design By <a href="https://www.creative-tim.com" target="_blank" rel="noopener">Creative
            Tim</a></p>
          <p>UI Library <a href="https://semantic-ui-vue.github.io/" target="_blank">Semantic UI</a>
          </p>
          <p>Editorjs by <a href="https://editorjs.io/" target="_blank">EditorJS.io</a></p>
          <p>Backend by <a href="https://github.com/noistudio/art_laravel" target="_blank">Art Laravel</a></p>

        </div>
      </div>
    </div>
  </footer>
</template>
<script>
export default {
  name: 'app-footer',
  data() {
    return {
      year: new Date().getFullYear()
    }
  }
};
</script>
<style>
</style>

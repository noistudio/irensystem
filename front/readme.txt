  Мануал по установке и настройке фронта для проектной системы.
  1. в /src/main.js
  меняем конфиги на свои
  Vue.config.API_URL = "https://api.artemdev.ru/api/";
  Vue.config.IMAGE_URL = "https://api.artemdev.ru";
  Vue.config.BASE_URL = "https://artemdev.ru";
  Vue.config.TG_BOT = "artemdevrubot";
  2.Меняем в /src/registerServiceWorker.js var base_url = "https://artemdev.ru/"; на свой URL фронта. Это нужно чтобы правильно работало PWA


  3.Меняем текст или там стили и т.д
  4. Собираем
  npm  install
  npm run build

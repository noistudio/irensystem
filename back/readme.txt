 Мини-мануал по установке и настройке backend для проектной системы
 Подготовка
0.Не забудьте создать директорию /files/tmpfiles
1. Создать файл .env по примеру .env.example
2. Cоздать файл /engine/.env по примеру /engine/.env.example
3. Залить все на сервер\запустить либо в докере.
4. Прописываете правильные доступы к БД во всех .env файлах
5. Получение токена для яндекс диска описывается тут https://github.com/leonied7/yandex-disk-api
6. FRONT_URL - URL фронта, он нужен чтобы отправлять правильный URL  в телегу
 Настройка 
После того как все настроили и наверное все правильно сделали. Дальше нужно сделать следующее
   Выполняем в консоли следующие команду 
   cd engine
   php artisan migrate 
 
Если все прошло ОКЕЙ 
 то вам нужно дальше настроить данные в бекенде
1. http://BACKEND_URL/adminsuperproject/content/manage/index/proj_statuses здесь прописываете статусы проекта\задач
2. http://BACKEND_URL/adminsuperproject/content/manage/index/proj_portfolio_categorys здесь указываете категории для портфолио
в эти категории смогут добавлять свои работы разрешенные исполнители
3. http://BACKEND_URL/adminsuperproject/content/manage/add/about  
в поле главная страница пишите О сайте
в поле хедер вставляйте код и меняйте на свой
   <div class="col-lg-6">
   <h1 class="display-3  text-white">Artemdev.ru
   <span>делаю невозможное возможным</span>
   </h1>
   <p class="lead  text-white">Здесь рождаются качественные web проекты.</p>

              </div>    
4.http://BACKEND_URL/adminsuperproject/content/manage/proj_users в данном разделе вы можете назначать роли исполнителей нужным вам людям
 ответственные появляются на главной странице и могут создавать и размещать портфолио
5.http://BACKEND_URL/adminsuperproject/content/manage/index/proj_users_categorys в данном разделе вы  разрешаете вашим разработчикам доступ к указанным категориям


   
    


   
   

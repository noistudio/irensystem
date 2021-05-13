<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer_id = DB::table('proj_users')->insertGetId(
            [
                'enable' => 1,
                'name' => 'Super Coder',
                'account' => 'Здесь какие то реквизиты',
                'avatar' => '/files/tmpfiles/default-avatar.png',
                'username' => 'developerusername',
                'telegram_id' => rand(1000, 100000),
                'isdeveloper' => 1,
                'api_token' => Str::random(10),
                'isteam' => 1,
                'job' => 'Тестовый исполнитель',


            ]
        );

        $client_id = DB::table('proj_users')->insertGetId(
            [
                'enable' => 1,
                'name' => 'Super Client',
                'account' => 'Здесь какие то реквизиты',
                'avatar' => '/files/tmpfiles/default-avatar.png',
                'username' => 'clientusername',
                'telegram_id' => rand(1000, 100000),
                'isdeveloper' => 0,
                'api_token' => Str::random(10),
                'isteam' => 0,
                'job' => 'Тестовый клиент',


            ]
        );


        $observer_id = DB::table('proj_users')->insertGetId(
            [
                'enable' => 1,
                'name' => 'Super Observer',
                'account' => 'Здесь какие то реквизиты',
                'avatar' => '/files/tmpfiles/default-avatar.png',
                'username' => 'observer',
                'telegram_id' => rand(1000, 100000),
                'isdeveloper' => 0,
                'api_token' => Str::random(10),
                'isteam' => 0,
                'job' => 'Тестовый клиент',


            ]
        );


        $blog_category_no_public_id = DB::table('blog_categorys')->insertGetId(
            [
                'enable' => 1,
                'title' => 'Категория Новости',
                'image' => '/files/tmpfiles/default-avatar.png',
                'ispublic' => 0,
                'ismain' => 1,
                'isprivate' => 0,
                'background' => '/files/tmpfiles/default-avatar.png',


            ]
        );

        $blog_category_public_id = DB::table('blog_categorys')->insertGetId(
            [
                'enable' => 1,
                'title' => 'Категория Жалобы',
                'image' => '/files/tmpfiles/default-avatar.png',
                'ispublic' => 1,
                'ismain' => 0,
                'isprivate' => 0,
                'background' => '/files/tmpfiles/default-avatar.png',


            ]
        );

        $blog_category_private_id = DB::table('blog_categorys')->insertGetId(
            [
                'enable' => 1,
                'title' => 'Категория Жалобы',
                'image' => '/files/tmpfiles/default-avatar.png',
                'ispublic' => 0,
                'ismain' => 0,
                'isprivate' => 1,
                'background' => '/files/tmpfiles/default-avatar.png',


            ]
        );

        DB::table('blog_categorys_access')->insertGetId(
            [
                'enable' => 1,
                'category_id' => $blog_category_no_public_id,
                'user_id' => $developer_id,
                'onlyread' => 1,
                'write' => 1,
                'isadmin' => 1,


            ]
        );
        DB::table('blog_categorys_access')->insertGetId(
            [
                'enable' => 1,
                'category_id' => $blog_category_public_id,
                'user_id' => $developer_id,
                'onlyread' => 1,
                'write' => 1,
                'isadmin' => 1,


            ]
        );

        DB::table('blog_categorys_access')->insertGetId(
            [
                'enable' => 1,
                'category_id' => $blog_category_private_id,
                'user_id' => $developer_id,
                'onlyread' => 1,
                'write' => 1,
                'isadmin' => 1,


            ]
        );

        DB::table('blog_categorys_access')->insertGetId(
            [
                'enable' => 1,
                'category_id' => $blog_category_private_id,
                'user_id' => $client_id,
                'onlyread' => 1,
                'write' => 0,
                'isadmin' => 0,


            ]
        );

        $portfolio_category = DB::table('proj_portfolio_categorys')->insertGetId(
            [
                'enable' => 1,
                'name' => 'Testcat',
            ]
        );

        $project_category = DB::table('proj_categorys')->insertGetId(
            [
                'enable' => 1,
                'name' => 'Разработка на Laravel',
                'image' => '/files/tmpfiles/default-avatar.png',
                'description' => '        <h3>Разрабатываю  необычные проекты:)</h3>
              <p>Огромный опыт в разработке любых типов веб-приложений позволяет делать любые типы веб-проектов на ваш вкус и цвет</p>
<p>Имею опыт создания  как fullstack разработчик, так и only backend. Имею опыт в разработке сложных фриланс-систем, CRM , SaaS, маркетплейсов , социальных сетей и прочих сложных систем. Загляните в портфолио и убедитесь сами</p>
<p>Разрабатываю обычно на Laravel + Vue.js .</p>
              <ul class="list-unstyled mt-5">
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <badge type="success" circle="" class="mr-3" icon="ni ni-settings-gear-65"></badge>
                    <h6 class="mb-0">Лучшие условия</h6>
                  </div>
                </li>
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <badge type="success" circle="" class="mr-3" icon="ni ni-html5"></badge>
                    <h6 class="mb-0">Результативность</h6>
                  </div>
                </li>
                <li class="py-2">
                  <div class="d-flex align-items-center">
                    <badge type="success" circle="" class="mr-3" icon="ni ni-satisfied"></badge>
                    <h6 class="mb-0">Поддержка огонь</h6>
                  </div>
                </li>
              </ul>',

            ]
        );

        DB::table('proj_users_categorys')->insertGetId(
            [
                'enable' => 1,
                'category_id' => $project_category,
                'user_id' => $developer_id,
            ]
        );

        DB::table("proj_portfolio")->insertGetId(
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'enable' => 1,
                'description' => 'LA LA LA LA LA LEND',
                'image' => '/files/tmpfiles/default-avatar.png',
                'date_start' => date('Y-m-d'),
                'date_end' => date('Y-m-d'),
                'name' => 'Work 1',
                'category_id' => $portfolio_category,
                'user_id' => $developer_id,
                'json' => '{"time":1620830582913,"blocks":[{"id":"5sq4WSJyS4","type":"header","data":{"text":"\u041f\u0440\u043e\u0435\u043a\u0442 \u0434\u043b\u044f \u041f\u0430\u0432\u043b\u0430","level":2}},{"id":"7ViKppDLPV","type":"paragraph","data":{"text":"\u0417\u0434\u0435\u0441\u044c \u043c\u043e\u0433\u0443\u0442 \u0431\u044b\u0442\u044c \u043e\u043f\u0438\u0441\u0430\u043d\u043e \u0431\u0430\u0437\u043e\u0432\u043e\u0435 \u043e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430. \u0430 \u0442\u0430\u043a\u0436\u0435 \u0440\u0430\u0437\u043d\u044b\u0445 \u0442\u0440\u0435\u0431\u043e\u0432\u0430\u043d\u0438\u0439 \u043d\u0430\u043f\u0440\u0438\u043c\u0435\u0440:"}},{"id":"hY5fiYoPZY","type":"list","data":{"style":"unordered","items":["\u0417\u043d\u0430\u0442\u044c Laravel","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 Vue.js","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 REST API"]}}],"version":"2.21.0"}',

            ]
        );

        DB::table("blog_posts")->insertGetId(
            [
                "created_at" => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i::s'),
                'enable' => 1,
                'user_id' => $developer_id,
                'category' => $blog_category_no_public_id,
                'disable_comments' => 0,
                'short' => '{"time":1620830582913,"blocks":[{"id":"5sq4WSJyS4","type":"header","data":{"text":"\u041f\u0440\u043e\u0435\u043a\u0442 \u0434\u043b\u044f \u041f\u0430\u0432\u043b\u0430","level":2}},{"id":"7ViKppDLPV","type":"paragraph","data":{"text":"\u0417\u0434\u0435\u0441\u044c \u043c\u043e\u0433\u0443\u0442 \u0431\u044b\u0442\u044c \u043e\u043f\u0438\u0441\u0430\u043d\u043e \u0431\u0430\u0437\u043e\u0432\u043e\u0435 \u043e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430. \u0430 \u0442\u0430\u043a\u0436\u0435 \u0440\u0430\u0437\u043d\u044b\u0445 \u0442\u0440\u0435\u0431\u043e\u0432\u0430\u043d\u0438\u0439 \u043d\u0430\u043f\u0440\u0438\u043c\u0435\u0440:"}},{"id":"hY5fiYoPZY","type":"list","data":{"style":"unordered","items":["\u0417\u043d\u0430\u0442\u044c Laravel","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 Vue.js","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 REST API"]}}],"version":"2.21.0"}',
                'content' => '{"time":1620830582913,"blocks":[{"id":"5sq4WSJyS4","type":"header","data":{"text":"\u041f\u0440\u043e\u0435\u043a\u0442 \u0434\u043b\u044f \u041f\u0430\u0432\u043b\u0430","level":2}},{"id":"7ViKppDLPV","type":"paragraph","data":{"text":"\u0417\u0434\u0435\u0441\u044c \u043c\u043e\u0433\u0443\u0442 \u0431\u044b\u0442\u044c \u043e\u043f\u0438\u0441\u0430\u043d\u043e \u0431\u0430\u0437\u043e\u0432\u043e\u0435 \u043e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430. \u0430 \u0442\u0430\u043a\u0436\u0435 \u0440\u0430\u0437\u043d\u044b\u0445 \u0442\u0440\u0435\u0431\u043e\u0432\u0430\u043d\u0438\u0439 \u043d\u0430\u043f\u0440\u0438\u043c\u0435\u0440:"}},{"id":"hY5fiYoPZY","type":"list","data":{"style":"unordered","items":["\u0417\u043d\u0430\u0442\u044c Laravel","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 Vue.js","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 REST API"]}}],"version":"2.21.0"}',


            ]
        );

        $status_freelance = DB::table("proj_statuses")->insertGetId(
            [
                'enable' => 1,
                '_lng' => '',
                'title' => 'Поиск исполнителя',
                'issearch' => 1,
                'isprice' => 0,
                'iswork' => 0,
                'isfinish' => 0,
            ]
        );

        $status_work = DB::table("proj_statuses")->insertGetId(
            [
                'enable' => 1,
                '_lng' => '',
                'title' => 'В работе',
                'issearch' => 0,
                'isprice' => 0,
                'iswork' => 1,
                'isfinish' => 0,
            ]
        );

        $status_work2 = DB::table("proj_statuses")->insertGetId(
            [
                'enable' => 1,
                '_lng' => '',
                'title' => 'В работе 2',
                'issearch' => 0,
                'isprice' => 0,
                'iswork' => 1,
                'isfinish' => 0,
            ]
        );

        DB::table("proj_projects")->insertGetId(
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'enable' => 0,
                'action' => '',
                'name_project' => 'Название вашего замечательного проекта!',
                'category_id' => $project_category,
                'start_time' => date('Y-m-d H:i:s'),
                'isclose' => 0,
                'developer_id' => 0,
                'client_id' => $client_id,
                'status' => $status_freelance,
                'json' => '{"time":1618521675369,"blocks":[{"type":"header","data":{"text":"\u041d\u0430\u0437\u0432\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u0437\u0430\u043c\u0435\u0447\u0430\u0442\u0435\u043b\u044c\u043d\u043e\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430!","level":2}},{"type":"paragraph","data":{"text":"\u0417\u0434\u0435\u0441\u044c \u043c\u043e\u0433\u0443\u0442 \u0431\u044b\u0442\u044c \u043e\u043f\u0438\u0441\u0430\u043d\u043e \u0431\u0430\u0437\u043e\u0432\u043e\u0435 \u043e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430. \u0430 \u0442\u0430\u043a\u0436\u0435 \u0440\u0430\u0437\u043d\u044b\u0445 \u0442\u0440\u0435\u0431\u043e\u0432\u0430\u043d\u0438\u0439 \u043d\u0430\u043f\u0440\u0438\u043c\u0435\u0440:"}},{"type":"list","data":{"style":"unordered","items":["\u0417\u043d\u0430\u0442\u044c Laravel","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 Vue.js","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 REST API"]}}],"version":"2.19.1"}',

            ]

        );

        $project_id = DB::table("proj_projects")->insertGetId(
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'enable' => 0,
                'action' => '',
                'name_project' => 'Название вашего замечательного проекта! 5',
                'category_id' => $project_category,
                'start_time' => date('Y-m-d H:i:s'),
                'isclose' => 0,
                'developer_id' => $developer_id,
                'client_id' => $client_id,
                'status' => $status_work,
                'json' => '{"time":1618521675369,"blocks":[{"type":"header","data":{"text":"\u041d\u0430\u0437\u0432\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u0437\u0430\u043c\u0435\u0447\u0430\u0442\u0435\u043b\u044c\u043d\u043e\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430!","level":2}},{"type":"paragraph","data":{"text":"\u0417\u0434\u0435\u0441\u044c \u043c\u043e\u0433\u0443\u0442 \u0431\u044b\u0442\u044c \u043e\u043f\u0438\u0441\u0430\u043d\u043e \u0431\u0430\u0437\u043e\u0432\u043e\u0435 \u043e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0432\u0430\u0448\u0435\u0433\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430. \u0430 \u0442\u0430\u043a\u0436\u0435 \u0440\u0430\u0437\u043d\u044b\u0445 \u0442\u0440\u0435\u0431\u043e\u0432\u0430\u043d\u0438\u0439 \u043d\u0430\u043f\u0440\u0438\u043c\u0435\u0440:"}},{"type":"list","data":{"style":"unordered","items":["\u0417\u043d\u0430\u0442\u044c Laravel","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 Vue.js","\u0423\u043c\u0435\u0442\u044c \u0440\u0430\u0431\u043e\u0442\u0430\u0442\u044c \u0441 REST API"]}}],"version":"2.19.1"}',

            ]

        );
        $project_invoice_id = DB::table("proj_project_invoices")->insertGetId(
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'enable' => 1,
                'project_id' => $project_id,
                'sum' => 1000,
                'currency' => 'RUB',
                'client_id' => $client_id,
                'developer_id' => $developer_id,
                'title' => 'FAST COMMENT',
                'is_approve_client' => 1,
                'is_finish' => 1,
                'final_invoice_id' => 0,
            ]
        );
        $invoice_id = DB::table("proj_invoices")->insertGetId(
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'enable' => 1,
                'project_id' => $project_id,
                'sum' => 1000,
                'currency' => 'RUB',
                'client_id' => $client_id,
                'developer_id' => $developer_id,
                'title' => 'FAST COMMENT',
                'ispay' => 0,
                'client_pay' => 1,
                'client_is_review' => 0,
                'developer_is_review' => 0,
            ]
        );

        DB::table('proj_project_invoices')->update(['final_invoice_id' => $invoice_id]);


        DB::table("pages")->insertGetId(
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'enable' => 1,
                "name" => "name1",
                "title" => "title1",
                "content" => "CONTENT",
                "icon" => "icon2",
                "description" => "DESCR 123123",

            ]
        );


    }
}
